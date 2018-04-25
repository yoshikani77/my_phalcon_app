<?php
use Phalcon\Mvc\View;
class EventController extends ControllerBase{

  public function beforeExecuteRoute(){ // function ที่ทำงานก่อนเริ่มการทำงานของระบบทั้งระบบ
	  if(!$this->session->has('memberAuthen')) // ตรวจสอบว่ามี session การเข้าระบบ หรือไม่
         $this->response->redirect('authen');  
   }
 
  public function indexAction(){
    $events=Event::find();
    $this->view->data=$events;
  }
  public function editAction(){
    $id=$this->dispatcher->getParam("id");
    $event=Event::findFirst("id=".$id);
    $this->view->data=$event;
    if($this->request->isPost()){
      $name = trim($this->request->getPost('name')); // รับค่าจาก form
      $date = trim($this->request->getPost('date')); // รับค่าจาก form
      $detail = trim($this->request->getPost('details')); // รับค่าจาก form

      $photoUpdate='';
      if($this->request->hasFiles() == true){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $uploads = $this->request->getUploadedFiles();
        $isUploaded = false;			
        foreach($uploads as $upload){
          if(in_array($upload->gettype(), $allowed)){					
            $photoName=md5(uniqid(rand(), true)).strtolower($upload->getname());
            $path = '../public/img/event/'.$photoName;
            ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
          }
        }
        if($isUploaded)  $photoUpdate=$photoName ;
        }else
        die('You must choose at least one file to send. Please try again.');
    $event->name=$name;
    $event->date=$date;
    $event->detail=$detail;
    $event->picture=$photoUpdate;
    $event->save();
    
    $this->flashSession->success('อัพเดทข้อมูลเรียบร้อยแล้ว');
    }
  }

  public function addAction(){
    if($this->request->isPost()){
      $name = trim($this->request->getPost('name')); // รับค่าจาก form
      $date = trim($this->request->getPost('date')); // รับค่าจาก form
      $detail = trim($this->request->getPost('details')); // รับค่าจาก form

      $photoUpdate='';
      if($this->request->hasFiles() == true){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $uploads = $this->request->getUploadedFiles();
        $isUploaded = false;			
        foreach($uploads as $upload){
          if(in_array($upload->gettype(), $allowed)){					
            $photoName=md5(uniqid(rand(), true)).strtolower($upload->getname());
            $path = '../public/img/event/'.$photoName;
            ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
          }
        }
        if($isUploaded)  $photoUpdate=$photoName ;
        }else
        die('You must choose at least one file to send. Please try again.');
    $event=new Event();
    $event->name=$name;
    $event->date=$date;
    $event->detail=$detail;
    $event->picture=$photoUpdate;
    $event->save();
    
    $this->flashSession->success('เพิ่มข้อมูลเรียบร้อยแล้ว');
    }
  }

  public function delAction(){
    $id=$this->dispatcher->getParam("id");
    $event=Event::findFirst("id=".$id);
    $event->delete();
    $this->response->redirect('event');
  }
}  