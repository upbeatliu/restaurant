<?php
session_start();
include_once('../inc/connection.php');
include_once('../inc/function.php');
$nav = new Nav;
$navs = $nav->fetch_all();
$combo = new ComboBox;
$model = $nav->fetch_model();

$table = new Table_heading;
$form = new form;

$nav = new Nav;
$id = 0;
if(isset($_GET['id']))
{
   $id = preg_replace("[^0-9]", "", $_GET['id']);
}

$data = $nav->fetch_data($id);
?>

<html>
   <head>
        <title> Yummy Restaurant</title>
        <link rel="stylesheet" href="../css/style.css">
    <script type="text/javascript">
	    function ConfirmDelete(memu_name)
		{
		   if(confirm("Would you like to delete record "+ memu_name +" ?"))
		   {
			   return true;
		   }
		   else
		   {
		       return false;
		   }
		}

			    function ConfirmUpdate(memu_name)
		{
		   if(confirm("Would you like to update record "+ memu_name +" ?"))
		   {
			   return true;
		   }
		   else
		   {
		       return false;
		   }
		}
	</script>
   </head>
   
<body>
	<div id="container">
           <div id="header"> <?php echo $model[0]['body'];  ?></div>      
                         
	   <div class="main">           
          <div class="tableLeft">
                 <br/><br/>
                 <h4>Edit and Delete Content</h4>
                <a href="adminIndex.php">&larr;Go Back Admin Main Page</a>
                <p>Please select a Subject to edit below.
                <?php $combo->AddComboBoxWithOnChange($navs, $id, "id", "menu_name", "edit.php", "id"); ?></p>

 <?php 
     $table->BeginTable("860","1","2");
          $table->BeginHeading();       
            $table->AddHeading("72", "#FFD7AE", "Remove");
	        $table->AddHeading("50", "#FFD7AE", "id");
            $table->AddHeading("76", "#FFD7AE", "Menu Name");
            $table->AddHeading("54", "#FFD7AE", "Position");
            $table->AddHeading("49", "#FFD7AE", "Visible");
            $table->AddHeading("360", "#FFD7AE", "Content");
            $table->AddHeading("69", "#FFD7AE", "Update");
          $table->EndHeading();           
            
				//echo $data['id'],$data['menu_name'];
				
          $table->BeginHeading();		      
    			$form->Begintd();
    			$form->BeginForm("formR".$data['id'], "remove","post", "delete.php?menu_id=".$data['id'], "return ConfirmDelete('".$data['menu_name']."')");
			    	$form->specialInput("submit", "submit", "Remove","");
				$form->EndForm();
				$form->Endtd();
						
				$form->BeginForm("fromU".$data['id'], "update","post", "update.php?id=".$data['id'], "return ConfirmUpdate('".$data['menu_name']."')");
				    $form->Input("text", "id", $data['id'],"1");
					$form->Input("text", "menu_name", $data['menu_name'],"6");
					$form->Input("text", "position", $data['position'],"1");
					$form->Input("text", "visible", $data['visible'],"1");
					$form->BeginTextarea("content", "5", "60%", "");
					echo $data['content'];
					$form->Endtextarea();
				    $form->Input("submit", "submit", "Update","");
				$form->EndForm();
          $table->EndHeading();
     $table->EndTable();	
          ?>
           </div> 
	    </div><!---end of main-->
	    <div id="footer"><?php echo $model[1]['body']; ?></div>
	</div><!--end of container-->
</body>
</html>