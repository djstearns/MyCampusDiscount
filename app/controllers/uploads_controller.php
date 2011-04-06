<?php
class UploadsController extends AppController {
        
	var $name = 'Uploads';
         var $components = array('Uploader.Uploader');
        function beforeFilter() {
            parent::beforeFilter();
            //$this->Auth->allowedActions = array('*');
        }
	function index() {
		$this->Upload->recursive = 2;
                
                //debugger::dump($this->Upload->User);
		$this->set('uploads', $this->paginate());
	}

	function view($id) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid upload', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('upload', $this->Upload->read(null, $id));
	}
/**
        function addBatch(){
            //Debugger::dump($this->data[Upload][fileName]);
            set_time_limit(300);
                //change below variable
                $modelName = 'MODELNAME';
                //echo FILEINFO_MIME_TYPE('N126081.000');
                if (!empty($this->data)) {
                    if ($data = $this->Uploader->upload('fileName')) {
                        // Upload successful, do whatever
			$this->Upload->create();
                        //Debugger::dump($this->data);
                        $this->data = $this->Upload->fillData($this);
			if ($this->Upload->save($this->data)) {
                                //$this->Upload->uploadData($this->data);
				$this->Session->setFlash(__('The upload has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
			}
                    }else{
                        $this->Session->setFlash(__('The upload failed!', true));
                        $this->redirect(array('action' => 'index'));
                    }
                }
		$users = $this->Upload->User->find('list');
		$this->set(compact('users','modelName'));

        }
 *
 */
        /**
         * This function builds the N155 File and aggregates all reports to one file.
         */
        function BuildN155000(){
            //check if companies were selected for a date
            if(!empty($this->data)){
                //!!HARD CODED DATA LOCATION DESTINATION!!
                $writeFile = 'G:\Everyone\AccessDatabaseSupport\AccessDatabaseApplicationFiles\combinedN155000.txt';
                //Check if the file dest. already exists...if it does clear, it.
                if(file_exists($writeFile)){
                    $data = "";
                    $fh = fopen($writeFile, 'w') or die("can't open file");
                    fwrite($fh, $data);
                    fclose($fh);
                }
                //count the number of days between
                $numOfDaysBetween = $this->Upload->count_days($this->data['Upload']['startdate'], $this->data['Upload']['enddate']);
                //format the start and end date from the form
                $stdate = $this->data['Upload']['startdate']['year'].'-'.$this->data['Upload']['startdate']['month'].'-'.$this->data['Upload']['startdate']['day'];
                $enddate = $this->data['Upload']['enddate']['year'].'-'.$this->data['Upload']['enddate']['month'].'-'.$this->data['Upload']['enddate']['day'];
                //get the companies desired for report.
                $companyArray = $this->data['Upload']['Company'];
                //Go through each company and retrieve each company's actual name (as opposed to 'id')
                foreach($companyArray as  $companyItem){
                    //set first date to start
                    //debugger::dump($coid);
                    $date = $stdate;
                    //get the actual company name
                    $coid = $this->Upload->Company->find('first', array('conditions' => array('id' => $companyItem)));
                    //debugger::dump($coid);
                    //for each date in the range...
                    while(strtotime($date) <= strtotime($enddate)){
                        //get the format date,...
                        $fdate = date("Ymd", strtotime($date));
                        //specify file name
                        $fileName = 'R:\\'.$coid['Company']['coid'].'\\'.$fdate.'\\N155000.000';
                        //check if our file exists,...
                        if(file_exists($fileName)){
                            //open the file to read
                            $f  = fopen($fileName, "r");
                            //if we can open the file......
                            if($f){
                                //read it,
                                $buff   = fread($f, filesize($fileName));
                                //close it
                                $f   = fclose($f);
                                //open our destination file
                                $f   = fopen($writeFile, "a");
                                //if we can open the destination file,
                                if ($f)
                                {
                                   //add and do not overwrite our original
                                   fwrite($f, chr(13).chr(10).'company:'.$coid['Company']['coid'].chr(13).chr(10).$buff);
                                   //close destination
                                   $f = fclose($f);
                                }
                            }

                        }
                        //go to the next date in this company folder
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    //end of DATE loop
                    }
                //end of company loop
                }
                //specify back up name
                $backupFileName = 'G:\Everyone\AccessDatabaseSupport\AccessDatabaseApplicationFiles\combinedN155000-'.date('Ymd-his').'.txt';
                //if you cant create a copy as a backup...
                if (!copy($writeFile, $backupFileName)) {
                     $this->Session->setFlash(__('Your file failed to backup.', true));
                }else{
                     $this->Session->setFlash(__('Your file has been successfully backed up to '.$backupFileName.'.', true));
                }
                $this->redirect(array('action' => 'addn155000'));
            //end check if data was submitted
            }
                $users = $this->Upload->User->find('list');
                $companies = $this->Upload->User->Dept->Company->find('list');

                $this->set(compact('users','modelName','companies'));
        //end function
        }

         function addn155000(){
                //set our model name
                $modelName = 'n155000';
                //check for data uploaded file in browser
                if (!empty($this->data)) {
                    //clear the import table
                    $test = $this->Upload->User->find('all', array('conditions'=> array('User.id'=> $this->LoadsysAuth->user('id')),'recursive'=>2));
                    foreach($test[0]['Dept']['Company'] as $comp){
                        $companies[] = $comp['name'];
                    }
                    $this->Upload->clearN155($companies);
                    //located in 'plugins>>uploader>>controllers>>components>>uploader.php'
                    //this will test for successful upload of the file
                    if ($data = $this->Uploader->upload('fileName')) {
                        // Upload successful start creating records
                        $this->Upload->create();
                        //fill records from data
                        //This is located in app_model!
                        $this->data = $this->Upload->fillData($this);
                        //save the data added.
                        if ($this->Upload->save($this->data)) {
                                //transfer N155000imports to production table
                                $this->Upload->transferN155ToProd();
                                $this->Session->setFlash(__('The upload has been saved22', true));
                                $this->Session->setFlash(__('The upload has been saved2', true));
                                $this->redirect(array('action' => 'index'));
                        } else {
                                $this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
                        }
                    }else{
                        $this->Session->setFlash(__('The upload failed for 1 or more companies!', true));
                        $this->redirect(array('action' => 'index'));
                    }


                }
                //$user->recursive(5);
                $test = $this->Upload->User->find('all', array('conditions'=> array('User.id'=> $this->LoadsysAuth->user('id')),'recursive'=>2));
                //debugger::dump($test[0]['Dept']['Company']);
                $this->set(compact('modelName'));

        }

        function BuildTaxfiles(){
            //check if companies were selected for a date
            set_time_limit(600);
            if(!empty($this->data)){
                //set the names of the tax files (CSV)
                $fileArray = $this->data['Upload']['Taxs'];
                //debugger::dump($fileArray);
                //$fileArray = array('T055011','T055021','T055031','T055041','T055051','T055061');
                //Parse through tax files
                foreach($fileArray as $fileToImport){
                    //!!HARD CODED DATA LOCATION DESTINATION!!
                    
                    $fileNameToImport = $this->Upload->Taxfile->find('first', array('conditions' => array('id' => $fileToImport)));
                    //debugger::dump($fileToImport);
                    $fileNameToImport = $fileNameToImport['Taxfile']['name'];
                    
                    $writeFile = 'G:\Everyone\AccessDatabaseSupport\AccessDatabaseApplicationFiles\combined'.$fileNameToImport.'.txt';
                    //Check if the file dest. already exists...if it does clear, it.
                    if(file_exists($writeFile)){
                        $data = "";
                        $fh = fopen($writeFile, 'w') or die("can't open file");
                        fwrite($fh, $data);
                        fclose($fh);
                    }else{
                        $fh = fopen($writeFile, 'w') or die("can't open file");
                        fwrite($fh, ' ');
                        fclose($fh);
                    }
                    //count the number of days between
                    $numOfDaysBetween = $this->Upload->count_days($this->data['Upload']['startdate'], $this->data['Upload']['enddate']);
                    //format the start and end date from the form
                    $stdate = $this->data['Upload']['startdate']['year'].'-'.$this->data['Upload']['startdate']['month'].'-'.$this->data['Upload']['startdate']['day'];
                    $enddate = $this->data['Upload']['enddate']['year'].'-'.$this->data['Upload']['enddate']['month'].'-'.$this->data['Upload']['enddate']['day'];
                    //get the companies desired for report.
                    $companyArray = $this->data['Upload']['Company'];
                    //Go through each company and retrieve each company's actual name (as opposed to 'id')
                    foreach($companyArray as  $companyItem){
                        //set first date to start
                        $date = $stdate;
                        //get the actual company name
                        $coid = $this->Upload->Company->find('first', array('conditions' => array('id' => $companyItem)));
                        //for each date in the range...
                        while(strtotime($date) <= strtotime($enddate)){
                            //get the format date,...
                            $fdate = date("Ymd", strtotime($date));
                            //specify file name
                            $fileName = 'R:\\'.$coid['Company']['taxgroupid'].'\\'.$fdate.'\\'.$fileNameToImport.'.000';
                            //check if our file exists,...
                            if(file_exists($fileName)){
                                //open the file to read
                                $f  = fopen($fileName, "r");
                                //if we can open the file......
                                if($f){
                                    //read it,
                                    $buff   = fread($f, filesize($fileName));
                                    //close it
                                    $f   = fclose($f);
                                    //open our destination file
                                    $f   = fopen($writeFile, "a");
                                    //if we can open the destination file,
                                    if ($f)
                                    {
                                       //add and do not overwrite our original
                                        
                                       fwrite($f, $buff);

                                       fputs($f,"\r\n");
                                       //close destination
                                       $f = fclose($f);
                                    }
                                }

                            }
                            //go to the next date in this company folder
                            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        //end of DATE loop
                        }
                    //end of company loop
                    }
                    //specify back up name
                    $backupFileName = 'G:\Everyone\AccessDatabaseSupport\AccessDatabaseApplicationFiles\combined'.$fileNameToImport.'-'.date('Ymd-his').'.txt';
                    $fh = fopen($backupFileName, 'w');
                    fwrite($fh, ' ');
                    fclose($fh);
                    //if you cant create a copy as a backup...
                    //if (!copy($writeFile, $backupFileName)) {
                    //     $this->Session->setFlash(__('Your file failed to backup.', true));
                    //}else{
                    //     $this->Session->setFlash(__('Your file has been successfully backed up to '.$backupFileName.'.', true));
                    //}
                    
                //move to next TAX FILE
                }
                $this->redirect(array('action' => 'addt055011'));
            //end check if data was submitted
            }
                $users = $this->Upload->User->find('list');
                $test = $this->Upload->User->find('all', array('conditions'=> array('User.id'=> $this->LoadsysAuth->user('id')),'recursive'=>2));
                foreach($test[0]['Dept']['Company'] as $comp){
                    $companies[] = $comp['name'];
                }

                //$companies = $this->Upload->User->Dept->Company->find('list');
                //debugger::dump($companies);
                $taxs = $this->Upload->Taxfile->find('list');
                
               
                $this->set(compact('users','modelName','companies','taxs'));
        //end function
        }

        function addt055011(){
                //set our model name
            
                $modelName = 't055011';
                //check for data uploaded file in browser
                if (!empty($this->data)) {
                    //clear the import table
                    $test = $this->Upload->User->find('all', array('conditions'=> array('User.id'=> $this->LoadsysAuth->user('id')),'recursive'=>2));
                    foreach($test[0]['Dept']['Company'] as $comp){
                        $companies[] = $comp['name'];
                    }
                    $this->Upload->cleartable('t055011imports', $companies);
                    //located in 'plugins>>uploader>>controllers>>components>>uploader.php'
                    //this will test for successful upload of the file
                    if ($data = $this->Uploader->upload('fileName')) {
                        // Upload successful start creating records
                        $this->Upload->create();
                        //fill records from data
                        //This is located in app_model!
                        $this->data = $this->Upload->fillData($this);
                        //save the data added.
                        if ($this->Upload->save($this->data)) {
                                //delete header rows
                                $this->Upload->deleteHeaders($modelName);
                                //transfer N155000imports to production table
                                //$this->Upload->transferN155ToProd();
                                $this->Session->setFlash(__('The upload has been saved', true));
                                $this->redirect(array('action' => 'index'));
                        } else {
                                $this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
                        }
                    }else{
                        $this->Session->setFlash(__('The upload failed for 1 or more companies!', true));
                        $this->redirect(array('action' => 'index'));
                    }


                }
                $this->set(compact('modelName'));

        }

        function addt055021(){
                //set our model name
                $modelName = 't055021';
                //check for data uploaded file in browser
                if (!empty($this->data)) {
                    //clear the import table
                    $test = $this->Upload->User->find('all', array('conditions'=> array('User.id'=> $this->LoadsysAuth->user('id')),'recursive'=>2));
                    foreach($test[0]['Dept']['Company'] as $comp){
                        $companies[] = $comp['name'];
                    }
                    $this->Upload->cleartable('t055021imports', $companies);
                    //located in 'plugins>>uploader>>controllers>>components>>uploader.php'
                    //this will test for successful upload of the file
                    if ($data = $this->Uploader->upload('fileName')) {
                        // Upload successful start creating records
                        $this->Upload->create();
                        //fill records from data
                        //This is located in app_model!
                        $this->data = $this->Upload->fillData($this);
                        //save the data added.
                        if ($this->Upload->save($this->data)) {
                                $this->Upload->deleteHeaders($modelName);
                                //transfer N155000imports to production table
                                //$this->Upload->transferN155ToProd();
                                $this->Session->setFlash(__('The upload has been saved', true));
                                $this->redirect(array('action' => 'index'));
                        } else {
                                $this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
                        }
                    }else{
                        $this->Session->setFlash(__('The upload failed for 1 or more companies!', true));
                        $this->redirect(array('action' => 'index'));
                    }


                }
                $this->set(compact('modelName'));

        }

        function addt055031(){
                //set our model name
                $modelName = 't055031';
                //check for data uploaded file in browser
                if (!empty($this->data)) {
                    //clear the import table
                    $test = $this->Upload->User->find('all', array('conditions'=> array('User.id'=> $this->LoadsysAuth->user('id')),'recursive'=>2));
                    foreach($test[0]['Dept']['Company'] as $comp){
                        $companies[] = $comp['name'];
                    }
                    $this->Upload->cleartable('t055031imports', $companies);
                    //located in 'plugins>>uploader>>controllers>>components>>uploader.php'
                    //this will test for successful upload of the file
                    if ($data = $this->Uploader->upload('fileName')) {
                        // Upload successful start creating records
                        $this->Upload->create();
                        //fill records from data
                        //This is located in app_model!
                        $this->data = $this->Upload->fillData($this);
                        //save the data added.
                        if ($this->Upload->save($this->data)) {
                                $this->Upload->deleteHeaders($modelName);
                                //transfer N155000imports to production table
                                //$this->Upload->transferN155ToProd();

                                $this->flash(__('The upload has been saved', true));
                                $this->Session->setFlash(__('The upload has been saved2', true));
                                //debugger::dump($this->Session);
                                $this->redirect(array('action' => 'index'));
                        } else {
                                $this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
                        }
                    }else{
                        $this->Session->setFlash(__('The upload failed for 1 or more companies!', true));
                        $this->redirect(array('action' => 'index'));
                    }


                }
                $this->set(compact('modelName'));

        }

        function addt055041(){
                //set our model name
                $modelName = 't055041';
                //check for data uploaded file in browser
                if (!empty($this->data)) {
                    //clear the import table
                    $test = $this->Upload->User->find('all', array('conditions'=> array('User.id'=> $this->LoadsysAuth->user('id')),'recursive'=>2));
                    foreach($test[0]['Dept']['Company'] as $comp){
                        $companies[] = $comp['name'];
                    }
                    $this->Upload->cleartable('t055041imports', $companies);
                    //located in 'plugins>>uploader>>controllers>>components>>uploader.php'
                    //this will test for successful upload of the file
                    if ($data = $this->Uploader->upload('fileName')) {
                        // Upload successful start creating records
                        $this->Upload->create();
                        //fill records from data
                        //This is located in app_model!
                        $this->data = $this->Upload->fillData($this);
                        //save the data added.
                        if ($this->Upload->save($this->data)) {
                                //transfer N155000imports to production table

                                //$this->Upload->transferN155ToProd();
                                $this->Session->setFlash(__('The upload has been saved', true));
                                $this->redirect(array('action' => 'index'));
                        } else {
                                $this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
                        }
                    }else{
                        $this->Session->setFlash(__('The upload failed for 1 or more companies!', true));
                        $this->redirect(array('action' => 'index'));
                    }


                }
                $this->set(compact('modelName'));

        }

        function addt055051(){
                //set our model name
                $modelName = 't055051';
                //check for data uploaded file in browser
                if (!empty($this->data)) {
                    //clear the import table
                    $test = $this->Upload->User->find('all', array('conditions'=> array('User.id'=> $this->LoadsysAuth->user('id')),'recursive'=>2));
                    foreach($test[0]['Dept']['Company'] as $comp){
                        $companies[] = $comp['name'];
                    }
                    $this->Upload->cleartable('t055051imports', $companies);
                    //located in 'plugins>>uploader>>controllers>>components>>uploader.php'
                    //this will test for successful upload of the file
                    if ($data = $this->Uploader->upload('fileName')) {
                        // Upload successful start creating records
                        $this->Upload->create();
                        //fill records from data
                        //This is located in app_model!
                        $this->data = $this->Upload->fillData($this);
                        //save the data added.
                        if ($this->Upload->save($this->data)) {
                                //transfer N155000imports to production table
                                //$this->Upload->transferN155ToProd();
                                $this->Session->setFlash(__('The upload has been saved', true));
                                $this->redirect(array('action' => 'index'));
                        } else {
                                $this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
                        }
                    }else{
                        $this->Session->setFlash(__('The upload failed for 1 or more companies!', true));
                        $this->redirect(array('action' => 'index'));
                    }


                }
                $this->set(compact('modelName'));

        }

        function addt055061(){
                //set our model name
                $modelName = 't055061';
                //check for data uploaded file in browser
                if (!empty($this->data)) {
                    //clear the import table
                    $test = $this->Upload->User->find('all', array('conditions'=> array('User.id'=> $this->LoadsysAuth->user('id')),'recursive'=>2));
                    foreach($test[0]['Dept']['Company'] as $comp){
                        $companies[] = $comp['name'];
                    }
                    $this->Upload->cleartable('t055061imports', $companies);
                    //located in 'plugins>>uploader>>controllers>>components>>uploader.php'
                    //this will test for successful upload of the file
                    if ($data = $this->Uploader->upload('fileName')) {
                        // Upload successful start creating records
                        $this->Upload->create();
                        //fill records from data
                        //This is located in app_model!
                        $this->data = $this->Upload->fillData($this);
                        //save the data added.
                        if ($this->Upload->save($this->data)) {
                                 //transfer N155000imports to production table
                                //$this->Upload->transferN155ToProd();
                                $this->Session->setFlash(__('The upload has been saved', true));
                                $this->redirect(array('action' => 'index'));
                        } else {
                                $this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
                        }
                    }else{
                        $this->Session->setFlash(__('The upload failed for 1 or more companies!', true));
                        $this->redirect(array('action' => 'index'));
                    }


                }
                $this->set(compact('modelName'));

        }


	function add() {
            //debugger::dump($this->Upload);
		if (!empty($this->data)) {
			$this->Upload->create();
                        //debugger::dump($this->data['Upload']);
			if ($this->Upload->save($this->data)) {
				$this->Session->setFlash(__('The upload has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
			}
		}
		//debugger::dump($this->LoadsysAuth->user('id'));
                $users = $this->LoadsysAuth->user('id');
		$this->set(compact('users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid upload', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Upload->save($this->data)) {
				$this->Session->setFlash(__('The upload has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The upload could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Upload->read(null, $id);
		}
		$users = $this->Upload->User->find('list');
		$this->set(compact('users'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for upload', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Upload->delete($id)) {
			$this->Session->setFlash(__('Upload deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Upload was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>