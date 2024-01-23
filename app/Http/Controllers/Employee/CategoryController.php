<?php

namespace App\Http\Controllers\Employee;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use Auth;
use DB;
class CategoryController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->url= url(request()->route()->getPrefix());
    $this->prefix= 'employee/inventory/category';
    $this->title= 'Categories';
    $this->perPage= 10;
  }

  /**
  * @purpose         :   Display a listing of the category
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */
  public function index(Request $request)
  {
    $category = Category::orderBy('id','desc')->paginate($this->perPage);
    if($request->ajax())
    {
      return view($this->prefix.'/search',['category'=>$category,'url'=>$this->url]);
    }
    return view($this->prefix.'/index',['category'=>$category,'url'=>$this->url,'title'=>$this->title]);
  }

  /**
  * @purpose         :   Show the form for creating a new category
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */
  public function create()
  {
    return view($this->prefix.'/add');
  }

  /**
  * @purpose         :   Store a newly created category in storage.
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   Request
  * @return          :   view
  */
  public function store(CategoryStoreRequest $request)
  {
    try
    {
      $id=null;
      if(!empty($request->name))
      {
        DB::beginTransaction();
        $request->name=trim($request->name);
        $createData = array(
          'name' => $request->name,
          'description' => $request->description,
          'parameter' => $request->parameter,
          'is_deleted' => $request->status,
          'added_by' => Auth::user()->id,
        );
        if(Category::updateOrCreate(['id'=>$id],$createData))
        {
          DB::commit();
          return redirect('category')->with('success','Category created successfully.');
        }
        else
        {
          DB::rollback();
          return redirect('category')->with('error','Something went wrong.');
        }
      }
    }
    catch (\Exception $e)
    {
      DB::rollback();
      $errors             = $e->getMessage();
      return redirect('category')->with('error',$errors);
    }
  }

  /**
  * @purpose         :   Display the specified category
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */
  public function show(Category $category)
  {
    return view($this->prefix.'/show',compact('category'));
  }

  /**
  * @purpose         :   Show the form for editing the specified category
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @param  \App\Category  $category
  * @return          :   view
  */
  public function edit($id)
  {
    $id = \Crypt::decrypt($id);
    $category = Category::findOrFail($id);
    return view($this->prefix.'/edit',compact('category'));
  }

  /**
  * @purpose         :   Update the specified category in storage.
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   PUT
  * @params          :   \Illuminate\Http\Request  $request
  * @params          :   App\Category  $inventoryItem
  * @return          :   view
  */
  public function update(CategoryStoreRequest $request, Category $category)
  {
    try
    {
      $id= $category->id;
      if(!empty($request->name))
      {
        DB::beginTransaction();
        $request->name=trim($request->name);
        $createData = array(
          'name' => $request->name,
          'description' => $request->description,
          'parameter' => $request->parameter,
          'is_deleted' => $request->status
        );
        if(Category::updateOrCreate(['id'=>$id],$createData))
        {
          DB::commit();
          return redirect('category')->with('success','Category updated successfully.');
        }
        else
        {
          DB::rollback();
          return redirect('category')->with('error','Something went wrong.');
        }
      }
    }
    catch (\Exception $e)
    {
      DB::rollback();
      $errors             = $e->getMessage();
      return redirect('category')->with('error',$errors);
    }
  }

  /**
  * @purpose         :   Remove the specified category from storage.
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   DELETE
  * @params          :   \Illuminate\Http\Request  $request
  * @return          :   view
  */
  public function destroy(Category $category)
  {
    $category->delete();
    return redirect()->route($this->url.'/index')->with('success','Category deleted successfully');
  }

  /**
  * @purpose         :   Searching for category
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   \Illuminate\Http\Request  $request
  * @return          :   view
  */
  public function categorySearch(Request $request)
  {
    $search = trim($request->input('search'));
    $entriesperpage = $this->perPage;

    if($search == 'empty' || $search == '')
    {
      $category = Category::orderBy('id','desc')->paginate($entriesperpage);
    }
    else if($search != '')
    {
      $category = Category::where(function($q) use ($search){
        $q->where('name','LIKE','%'.$search.'%');
      })
      ->orderBy('id','desc')
      ->paginate($entriesperpage);
    }
    return view($this->prefix.'/search', ['category'=>$category]);
  }

  /**
  * @purpose         :   Change status of category
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :    $id
  * @return          :   view
  */
  public function change_category_status($id){
    $id = \Crypt::decrypt($id);
    $category = Category::findOrFail($id);
    if(!empty($category->is_deleted)){
      $status='0';
    }else{
      $status='1';
    }
    $category->is_deleted=$status;
    $category->update();
    return redirect('category')->with('success', 'Category status changed successfully!');
  }
}
