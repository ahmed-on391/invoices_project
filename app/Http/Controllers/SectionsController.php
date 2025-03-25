<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = sections::all();
        return view('sections.sections' , compact('sections'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
            'description' =>'required',
            ],[
                'section_name.required' => 'يجب ادخال اسم القسم',
                'section_name.unique' => 'اسم القسم موجود مسبقا',
                'description.required' => 'يجب ادخال وصف القسم',
            
        ]);
        // $input = $request->all();

        // $b_exists = sections::where('section_name', '=', $input['section_name'])->exists();//$b_exists: هذا المتغير يُستخدم لتخزين نتيجة استعلام قاعدة البيانات. يُفحص ما إذا كانت هناك أقسام (sections) تحمل اسمًا مطابقًا للقيمة المحددة في $input['section_name'].
        // //->exists(): هذه الدالة تُعيد true إذا كان هناك سجلات تطابق الشرط، و false إذا لم يتم العثور على أي سجلات
        // if($b_exists){
        //     session()->flash('Error', 'خطا القسم مسجل سابقا');//هذا الكود يستخدم لإضافة رسالة تنبيه وكمان  هو عرض رسالة خطأ للمستخدم بناءً على الشرط المحدد
        //     return redirect('/sections');
        // }
        // else{
            sections::create([
                'section_name'=>$request->section_name,
                'description'=>$request->description,
                'Created_by'=>(Auth::user()->name),
            ]);
            session()->flash('Add', 'تم اضافة القسم بنجاح');
            return redirect('/sections');
    //    }
    }

    /**
     * Display the specified resource.
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        
        $id = $request->id;

        $validated = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
            'description' =>'required',
            ],[
                'section_name.required' => 'يجب ادخال اسم القسم',
                'section_name.unique' => 'اسم القسم موجود مسبقا',
                'description.required' => 'يجب ادخال وصف القسم',
            
        ]);
        
        $sections= sections::find($id);
        
        $sections->update([
            'section_name'=>$request->section_name,
         'description'=>$request->description,
         ]);
    
    
         session()->flash('edit', 'تم تعديل القسم بنجاح');
                 return redirect('/sections');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('delete', 'تم حذف القسم بنجاح');
        return redirect('/sections');
        
    }
}
