<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\SlideRequest;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SlideController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->data['currentAdminMenu'] = 'general';
        $this->data['currentAdminSubMenu'] = 'slide';

        $this->data['statuses'] = Slide::STATUSES;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['slides'] = Slide::orderBy('position', 'ASC')->paginate(10);

        return view('admin.slides.index', $this->data);
    }

    /**
     * Move up the slide position
     *
     * @param int $id slide ID
     *
     * @return \Illuminate\Http\Response
     */
    public function moveUp($id)
    {
        $slide = Slide::findOrFail($id);

        if (!$slide->prevSlide()) {
            session()->flash('error', 'Invalid position');
            return redirect('admin/slides');
        }

        DB::transaction(
            function () use ($slide) {
                $currentPosition = $slide->position;
                $prevPosition = $slide->prevSlide()->position;

                $prevSlide = Slide::find($slide->prevSlide()->id);
                $prevSlide->position = $currentPosition;
                $prevSlide->save();

                $slide->position = $prevPosition;
                $slide->save();
            }
        );

        return redirect('admin/slides');
    }

    /**
     * Move down the slide position
     *
     * @param int $id slide ID
     *
     * @return \Illuminate\Http\Response
     */
    public function moveDown($id)
    {
        $slide = Slide::findOrFail($id);

        if (!$slide->nextSlide()) {
            session()->flash('error', 'Invalid position');
            return redirect('admin/slides');
        }

        DB::transaction(
            function () use ($slide) {
                $currentPosition = $slide->position;
                $nextPosition = $slide->nextSlide()->position;

                $nextSlide = Slide::find($slide->nextSlide()->id);
                $nextSlide->position = $currentPosition;
                $nextSlide->save();

                $slide->position = $nextPosition;
                $slide->save();
            }
        );

        return redirect('admin/slides');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['slide'] = null;

        return view('admin.slides.form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SlideRequest $request request params
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SlideRequest $request)
    {
        $params = $request->except('_token');

        $image = $request->file('image');
        $name = Str::slug($params['title']) . '_' . time();
        $fileName = $name . '.' . $image->getClientOriginalExtension();

        $folder = Slide::UPLOAD_DIR . '/images';

        $params['url'] = $image->storeAs($folder . '/original', $fileName, 'public');

        $params['user_id'] = Auth::user()->id;

        unset($params['image']);

        $params['position'] = Slide::max('position') + 1;

        if (Slide::create($params)) {
            session()->flash('success', 'Slide has been created');
        } else {
            session()->flash('error', 'Slide could not be created');
        }

        return redirect('admin/slides');
    }

    /**
     * Resize image
     *
     * @param file   $image    raw file
     * @param string $fileName image file name
     * @param string $folder   folder name
     *
     * @return Response
     */
    private function _resizeImage($image, $fileName, $folder)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id slide ID
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slide = Slide::findOrFail($id);

        $this->data['slide'] = $slide;

        return view('admin.slides.form', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SlideRequest $request request params
     * @param int          $id      slide ID
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SlideRequest $request, $id)
    {
        $params = $request->except('_token');

        $slide = Slide::findOrFail($id);
        if ($slide->update($params)) {
            session()->flash('success', 'Slide has been updated.');
        }

        return redirect('admin/slides');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id slide ID
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slide  = Slide::findOrFail($id);

        if ($slide->delete()) {
            session()->flash('success', 'Slide has been deleted');
        }

        return redirect('admin/slides');
    }
}
