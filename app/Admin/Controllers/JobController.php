<?php

namespace App\Admin\Controllers;

use App\Models\Job;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class JobController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.position'));
            $content->description(trans('admin::lang.list'));

            $content->body($this->grid());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Job::class, function (Grid $grid) {
            //拼接链接
            $grid->title(trans('admin::lang.job_title'))->display(function () {
                return "<a href='$this->url' target='_blank'>$this->title</a>";
            });
            $grid->column('salary', trans('admin::lang.job_salary'))->display(function () {
                return $this->salary_min . "-" . $this->salary_max;
            });
            $grid->welfare(trans('admin::lang.job_welfare'));
            $grid->number(trans('admin::lang.job_number'));
            $grid->experience(trans('admin::lang.job_experience'));
            $grid->education(trans('admin::lang.job_education'));
            $grid->company(trans('admin::lang.job_company'));
            $grid->address(trans('admin::lang.job_address'));
            $grid->scale(trans('admin::lang.job_scale'));
            $grid->nature(trans('admin::lang.job_nature'));
            $grid->industry(trans('admin::lang.job_industry'));
            $grid->publish_date(trans('admin::lang.job_publish_date'));

            //禁用批量删除  $grid->disableBatchDeletion();
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                     $actions->disableDelete();  //禁用批量删除
                });
            });
            $grid->disableActions();   //不显示编辑删除
            $grid->disableCreation();  //不显示新建
            $grid->disableRowSelector();  //禁用checkoutbox

            //条件筛选查询
            $grid->filter(function ($filter) {
                /*$filter->where(function ($query) {
                    $query->where('title', 'like', "%{$this->input}%")
                        ->orWhere('company', 'like', "%{$this->input}%")
                        ->orWhere('address', 'like', "%{$this->input}%");
                }, trans('admin::lang.job_title_company_address'));*/
                $filter->like('title', trans('admin::lang.job_title'));
                $filter->where(function ($query) {
                    $query->where('company', 'like', "%{$this->input}%")
                        ->orWhere('address', 'like', "%{$this->input}%");
                }, trans('admin::lang.job_company_address'));
                $filter->where(function ($query) {
                    $query->whereRaw("salary_max >= {$this->input}");
                }, trans('admin::lang.job_salary'));
                //$filter->disableIdFilter();
            });
        });
    }
}
