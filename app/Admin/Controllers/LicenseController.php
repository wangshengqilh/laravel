<?php

namespace App\Admin\Controllers;

use App\Model\LicenseModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LicenseModel);

        $grid->id('Id');
        $grid->name('Name');
        $grid->type('Type');
        $grid->home('Home');
        $grid->user('User');
        $grid->num('Num');
        $grid->addtime('Addtime');
        $grid->endtime('Endtime');
        $grid->scope('Scope');
        $grid->status('Status');
        $grid->appid('Appid');
        $grid->key('Key');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $appid=Str::random(10);
        $key=Str::random(5);
        $data=LicenseModel::where('id',$id)->first();
        if($data->status==1){
            $arr=LicenseModel::where('id',$id)->update(['status'=>0,'appid'=>$appid,'key'=>$key]);
        }
        $show = new Show(LicenseModel::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->type('Type');
        $show->home('Home');
        $show->user('User');
        $show->num('Num');
        $show->addtime('Addtime');
        $show->endtime('Endtime');
        $show->scope('Scope');
        $show->status('Status');
        $show->appid('Appid');
        $show->key('Key');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new LicenseModel);

        $form->text('name', 'Name');
        $form->text('type', 'Type');
        $form->text('home', 'Home');
        $form->text('user', 'User');
        $form->number('num', 'Num');
        $form->text('addtime', 'Addtime');
        $form->text('endtime', 'Endtime');
        $form->text('scope', 'Scope');
        $form->switch('status', 'Status')->default(1);

        return $form;
    }
}
