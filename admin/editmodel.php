<?php
session_start();
include_once('../inc/connection.php');
include_once('../inc/function.php');
$nav = new Nav;
$navs = $nav->fetch_model();

$combo = new ComboBox;
$model = $nav->fetch_model();

$table = new Table_heading;
$form = new form;

$nav = new Nav;
$id = 1; // default value
if(isset($_GET['id']))
{
   $id = preg_replace("[^0-9]", "", $_GET['id']);
}

$data = $nav->fetch_modeldata($id);
?>

<html>
   <head>
        <title> Yummy Restaurant</title>
        <link rel="stylesheet" href="../css/style.css">
    <script type="text/javascript">
	    
	    function ConfirmUpdate(name)
		{
		   if(confirm("Would you like to update record "+ name +" ?"))
		   {
			   return true;
		   }
		   else
		   {
		       return false;
		   }
		}		
	</script>
    
   <script language="javascript1.2">   

    var mysettings = new WYSIWYG.Settings();
	// define the location of the openImageLibrary addon
	mysettings.ImagePopupFile = "addons/imagelibrary/insert_image.php";
	// define the width of the insert image popup
	mysettings.ImagePopupWidth = 600;
	// define the height of the insert image popup
	mysettings.ImagePopupHeight = 245;

   //Image settings
   WYSIWYG.attach('body', mysettings);
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
                <p>Please select a Section to edit below.
                <?php $combo->AddComboBoxWithOnChange($navs, $id, "id", "name", "editmodel.php", "id"); ?></p>

           <?php   	
            $table->BeginTable("860","1","2");
            $table->BeginHeading();       
            
			$table->AddHeading("50", "#FFD7AE", "id");
            $table->AddHeading("76", "#FFD7AE", "Section Name");
            
            $table->AddHeading("49", "#FFD7AE", "Visible");
            $table->AddHeading("360", "#FFD7AE", "Body");
            $table->AddHeading("69", "#FFD7AE", "Update");
            $table->EndHeading();
				
            $table->BeginHeading();		      
    	    	$form->BeginForm("fromU".$data['id'], "update","post", "updatemodel.php?id=".$data['id'], "return ConfirmUpdate('".$data['name']."')");
				    $form->Input("text", "id", $data['id'],"1");
					$form->Input("text", "name", $data['name'],"6");
					$form->Input("text", "visible", $data['visible'],"1");
					$form->BeginTextarea("body", "5", "60%", "");
					echo $data['body'];
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