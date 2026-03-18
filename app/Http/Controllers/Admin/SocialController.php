<?php

namespace App\Http\Controllers\Admin;

use App\Models\SocialLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socials = SocialLink::latest()->get();

        return view('admin.socialLink.index',compact('socials'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.socialLink.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required', // Add file validation
            'link' => 'required', // Validate the link to be a valid URL
        ]);
        //  return $request;



        SocialLink::create([
            'icon' => $request->name,
            'link' => $request->link,
        ]);
        return redirect()->route('social-link.index')
        ->with(['status' => true, 'message' => 'Social link Created Successfully']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $social = SocialLink::find($id);

       return view('admin.socialLink.edit',compact('social'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        {
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255', // Validation for platform name
                'link' => 'required|url', // Ensure the link is a valid URL
            ]);

            // Find the social link by ID
            $socialLink = SocialLink::findOrFail($id);

            // Update the social link's data
            $socialLink->update([
                'icon' => $request->input('name'), // Platform name (icon)
                'link' => $request->input('link'), // Social media link
            ]);

            // Redirect back to the index page with success message
            return redirect()->route('social-link.index')
                             ->with(['status' => true, 'message' => 'Social link Updated Successfully']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       SocialLink::destroy($id);
       return redirect()->route('social-link.index')
                             ->with(['status' => true, 'message' => 'Social link Deleted Successfully']);
    }
}
