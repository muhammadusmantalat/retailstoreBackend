<?php

namespace App\Jobs;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Department;
use App\Models\AssignVendor;
use App\Models\ProductAssign;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAssignToVendor;
use Illuminate\Queue\SerializesModels;
use App\Models\AssignVendorToDepartment;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ProductAssignToDepartment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class BulkUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $rows;
    protected $storeId;
    protected $authId;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rows, $storeId, $authId)
    {
        $this->rows = $rows;
        $this->storeId = $storeId;
        $this->authId = $authId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Process each row in the CSV
        foreach ($this->rows as $row) {
            try {
                $upc_ipc = isset($row[0]) ? trim($row[0]) : null;
                $department_names = isset($row[1]) ? explode(',', $row[1]) : [];
                $price = isset($row[2]) ? trim($row[2]) : null;
                $product_name = isset($row[3]) ? trim($row[3]) : null;
                $tax_statuses = isset($row[4]) ? explode(',', $row[4]) : [];
                $vendor_names = isset($row[5]) ? explode(',', $row[5]) : [];
                $product_prices = isset($row[6]) ? explode(',', $row[6]) : [];

                foreach ($vendor_names as $index => $vendor_name) {
                    // Vendor processing
                    $vendor_name = trim($vendor_name);
                    if (empty($vendor_name)) continue;

                    $vendor = Vendor::firstOrCreate(
                        ['vendor_name' => $vendor_name, 'general_discount' => '0'],
                        ['vendor_name' => $vendor_name]
                    );

                    if ($vendor) {
                        // Further processing for departments, products, and assignments
                        foreach ($department_names as $dept_name_index => $dept_name) {
                            $department_name = trim($dept_name);
                            $tax_status_value = (isset($tax_statuses[$dept_name_index]) && $tax_statuses[$dept_name_index] === "taxable") ? 1 : 0;

                            $department = Department::firstOrCreate([
                                'store_manager_id' => $this->authId,
                                'store_id' => $this->storeId,
                                'department_name' => $department_name,
                                'tax_status' => $tax_status_value,
                            ]);
                            
                            // Assign vendor to department and product handling
                            $assignVendor = AssignVendor::firstOrCreate([
                                'store_manager_id' =>  $this->authId,
                                'store_id' => $this->storeId,
                                'vendor_id' => $vendor->id,
                            ]);

                            AssignVendorToDepartment::firstOrCreate([
                                'store_manager_id' =>  $this->authId,
                                'store_id' => $this->storeId,
                                'vendor_id' => $vendor->id,
                                'department_id' => $department->id,
                                'assignVendor_id' => $assignVendor->id,

                            ]);

                            // Product assignment
                            if ($product_name && $upc_ipc && $price) {
                                $product = Product::updateOrCreate(
                                    [
                                        'upc_ipc' => $upc_ipc,
                                        'store_manager_id' => $this->authId,
                                        'store_id' => $this->storeId,
                                    ],
                                    [
                                        'product_name' => $product_name,
                                        'price' => $price,
                                    ]
                                );

                                if ($product) {
                                    // Other product assignments
                                    ProductAssign::updateOrCreate([
                                        'store_manager_id' =>  $this->authId,
                                        'store_id' => $this->storeId,
                                        'product_id' => $product->id,
                                    ]);

                                    ProductAssignToDepartment::updateOrCreate([
                                        'store_manager_id' => $this->authId,
                                        'store_id' => $this->storeId,
                                        'department_id' => $department->id,
                                        'product_id' => $product->id,
                                    ]);

                                    ProductAssignToVendor::updateOrCreate([
                                        'store_manager_id' =>  $this->authId,
                                        'store_id' => $this->storeId,
                                        'vendor_id' => $vendor->id,
                                        'product_id' => $product->id,
                                        'department_id' => $department->id,
                                    ], [
                                        'product_price' => isset($product_prices[$index]) ? $product_prices[$index] : null,
                                    ]);
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error processing row: " . $e->getMessage());
            }
        }
    }
}
