<?php
namespace Home\Controller;
use Think\Controller;

class ModalController extends Controller {
//模态框配置
	public function toconfig()
	{
		$modal='<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title text-center" >
               预约设置【直接提交会默认执行】<span class="label label-warning">设置很重要</span>
            </h4>
         </div>
         <div class="modal-body text-center" id="agtext">
          <form class="form-inline"  role="form" id="sif">
            <div class="form-group">
              <input type="text" class="form-control" id="name1" placeholder="原人员">=>
              <input type="text" class="form-control" id="bname1" placeholder="替换人员">
          </div>
          <div class="form-group">
              <input type="text" class="form-control" id="name2" placeholder="原人员">=>
              <input type="text" class="form-control" id="bname2" placeholder="替换人员">
          </div>
          <div class="form-group">
              <input type="text" class="form-control" id="name3" placeholder="原人员">=>
              <input type="text" class="form-control" id="bname3" placeholder="替换人员">
          </div>
      </form>
      <div class="btn-group " data-toggle="buttons">
              <label class="btn btn-primary" id="one">刘胡平</label>
          <label class="btn btn-primary" id="two">吴密情</label>
      </div>
      <span class="btn btn-danger" id="add">增加</span>
         </div>
         <div class="modal-footer">
            <span  class="btn btn-default" 
               data-dismiss="modal">关闭
            </span>
            <span class="btn btn-primary" id="agsub">提交</span>
         </div>
      </div>
</div>
</div>';
		echo $modal;
	}
//永久配置模态框
	public function yconfig()
	{
		$modal='<div class="modal fade" id="configure" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title text-center" >
               表格设置【修改区域更换人员】<span class="label label-warning">请确保数据的正确</span>
            </h4>
         </div>
         <div class="modal-body text-center" id="yconfigtext">
          <form class="form-inline"  role="form" id="ysif">
            <div class="form-group">
              <input type="text" class="form-control" id="yname1" placeholder="原人员">=>
              <input type="text" class="form-control" id="ybname1" placeholder="替换人员">
          </div>
          <div class="form-group">
              <input type="text" class="form-control" id="yname2" placeholder="原人员">=>
              <input type="text" class="form-control" id="ybname2" placeholder="替换人员">
          </div>
          <div class="form-group">
              <input type="text" class="form-control" id="yname3" placeholder="原人员">=>
              <input type="text" class="form-control" id="ybname3" placeholder="替换人员">
          </div>
      </form>
      <span class="btn btn-danger" id="yadd">增加</span>
         </div>
         <div class="modal-footer">
            <span  class="btn btn-default" 
               data-dismiss="modal">关闭
            </span>
            <span class="btn btn-primary" id="yagsub">提交</span>
         </div>
      </div>
</div>
</div>';
	echo $modal;
	}
//导入excel模态框
	public function importexcel()
	{
		$modal='<div class="modal fade" id="newbl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
            <h4 class="modal-title text-center" >
               <span class="label label-success">导入表格数据</span>
            </h4>
         </div>
         <div class="modal-body text-center" id="modalinfo">
          <form class="form-inline" role="form" action="__MODULE__/Base/upload" enctype="multipart/form-data" method="post" >
         <div class="form-group">
            <label for="inputfile">上传数据表格</label>
            <input type="file" name="newinfofile" id="newinfofile" class="btn-primary">
            <!-- <progress value="0" max="100" id="upfilejd"></progress>  -->
         </div>
        </form>
        
         </div>
         <div class="modal-footer">
            <span  class="btn btn-info" data-dismiss="modal">关闭</span>
            <button type="button" class="btn btn-primary" id="newblsub">提交</button>            
         </div>
      </div>
</div>
</div>';
echo $modal;
	}
//导入维护excel模态框
	public function importwh()
	{
		$modal='<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
            <h4 class="modal-title text-center" >
               <span class="label label-success">导入小区维护表格</span>
            </h4>
         </div>
         <div class="modal-body text-center" id="modalinfo">
        <form class="form-inline" role="form" action="__MODULE__/Base/upload" enctype="multipart/form-data" method="post" >
         <div class="form-group">
            <label for="inputfile">上传维护表格</label>
            <input type="file" name="upfile" id="upfile" class="btn-primary">
         </div>
        </form>
         </div>
         <div class="modal-footer">
            <span  class="btn btn-info" data-dismiss="modal">关闭</span>
            <button type="button" class="btn btn-primary" id="whsub">提交</button>            
         </div>
      </div>
</div>
</div>';
echo $modal;
	}
//更改维护表格的模态框
	public function change()
	{
		$modal='<div class="modal fade" id="change" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
            <h4 class="modal-title text-center" >
               <span class="label label-success">导入小区维护表格</span>
            </h4>
         </div>
         <div class="modal-body text-center" id="modalinfo">
          <label for="inputfile">选择需要修改的表格</label>
        <div class="btn-group">
          <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown">
          <span class="sheet" id="needchange">all-所有表格</span><span style="display:none;">all</span><span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu" id="showsheet"></ul>
        </div>

         </div>
         <div class="modal-footer">
            <span  class="btn btn-info" data-dismiss="modal">关闭</span>
            <button type="button" class="btn btn-primary" id="changesheet">提交</button>            
         </div>
      </div>
</div>
</div>';
	echo $modal;
	}
//发送email的模态框
  public function suggest()
  {
     $modal='<div class="modal fade" id="suggestinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
            <h4 class="modal-title text-center" >
               <span class="label label-success">您的意见或建议：</span>
            </h4>
         </div>
         <div class="modal-body text-center" id="suginfo">
            <form role="form">
              <div class="form-group">
                <textarea class="form-control" rows="5" id="suggest"></textarea>
              </div>
            </form>
         </div>
         <div class="modal-footer">
            <span  class="btn btn-warning" id="sugsub">提交</span>           
         </div>
      </div>
</div>
</div>';
  echo $modal;
  }
}