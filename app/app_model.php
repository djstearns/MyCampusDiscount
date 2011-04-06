<?php
App::import('Vendor','php-excel-reader/excel_reader2');
App::import('Sanitize');
class AppModel extends Model {
         function beforeSave() {
                $exists = $this->exists();
                if ( !$exists && $this->hasField('creator_id') && empty($this->data[$this->alias]['creator_id']) ) {
                        $this->data[$this->alias]['creator_id'] = LoadsysAuth::getUserId();
                }
                if ( $this->hasField('modifier_id') && empty($this->data[$this->alias]['modifier_id']) ) {
                        $this->data[$this->alias]['modifier_id'] = LoadsysAuth::getUserId();
                }
                return true;
        }
       


        Public function fillData($thisd){
            set_time_limit(3000000);

        //this function simply copies the file from its original location, renames it
        //and saves it to a specified upload area.  It also adds the file record to the upload
        //table log.  Then it precedes to the function "uploadData" to actually import the file's
        //information.  To use this process you must implement the following function in the Upload Controller
        /*
         function addusbank(){
            //Debugger::dump($this->data[Upload][fileName]);
            set_time_limit(300);
                //This must reflect the name of your model to be imported to, and must be the prefix of your
                //import table such as "ustransactionsIMPORT"
                $modelName = 'ustransactions';
                //This command can be used to find the MIME type of the file
                //echo FILEINFO_MIME_TYPE('N126081.000');
                if (!empty($this->data)) {
                    if ($data = $this->Uploader->upload('fileName')) {
                        // Upload successful, do whatever
			$this->Upload->create();
                        //Debugger::dump($this->data);
                        $this->data = $this->Upload->fillData($this);

			if ($this->Upload->save($this->data)) {
                                //$this->data = $this->Upload->uploadData($this, array('postdate'=> array('type'=>'date'),
                                                                             //'transdate' => array('type' => 'date'),
                                                                             //'type'=> array('type'=>'varchar'),
                                                                             //'description' => array('type' => 'varchar'),
                                                                             //'category' => array('type' => 'text'),
                                                                             //'amount' => array('type'=> 'decimal')
                                                                            //), 'transactions');

                                //$this->Upload->uploadData($this->data);
                                //Other items can be done to the data HERE: such as "add new categories"

				$this->Session->setFlash(__('The upload has been saved', true));
				$this->redirect(array('controller' => 'ustransactionsimports', 'action' => 'index'));
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

         */
        //ensure that the file name is available for upload
        if(!empty($thisd->data['Upload']['fileName'])){
                        $imgDirFileName = 'images/uploads/'.$thisd->data['Upload']['fileName']['name'];
                        $ext = substr($imgDirFileName, strrpos($imgDirFileName, '.') + 1);
                        $newRandomFileName = 'files/uploads/'.rand(99,999999).'.'.$ext;
                        rename('files/uploads/'.$thisd->data['Upload']['fileName']['name'], $newRandomFileName);
                        $thisd->data['Upload']['fdir'] = $newRandomFileName;
                        $thisd->data['Upload']['ftype'] = $thisd->data['Upload']['fileName']['type'];
                        $thisd->data['Upload']['fsize']  = $thisd->data['Upload']['fileName']['size'];

            //Specific for TAX DATABASE
                        //debugger::dump($thisd->data['Upload']['fileName']['name']);
                        if(strpos($thisd->data['Upload']['fileName']['name'],'N155000') === false){

                            $N155 = '';
                        }else{
                            $N155 = 'N155';
                        }
                        
                        $tot_lines = $this->uploadData($thisd, $N155);
                        
                        /*  ORIGINAL
                        $this->uploadData($thisd);
                         */
                }
                return $thisd->data;
        }
        //Method N155 added for TAX DATABASE
        function uploadData($thisd, $N155 = false) {
            //this function actually imports information catptured from a copied file in the
            //preceeding function.  First it will copy the file over and will include in the data
            //the files appropriate model name in such "data['Upload']['model'].  It then assumes,
            //as in the directions above, that the records should be imported to table with the model prefix
            //and "import" postfix (e.g. MyImportTableIMPORT).  The function then detects which type of file
            //is being added via MIME-TYPE (see above to determine if unknown).  The function parses the data
            //into an array and then goes through the specified model to verify the data types to build each
            //SQL statement.

            $thisd->loadModel(ucfirst(strtolower($thisd->data['Upload']['model'])).'import');
            //Debugger::dump($thisd->name);
            if (!empty($thisd->data)) {
                    //bulk uploader
                         //get file to open
                            $fileToOpen = $thisd->data['Upload']['fdir'];
                            //$filePathArray = explode('/',$thisd->data['Upload']['fileName']);
                            //debugger::dump($thisd->data['Upload']['fileName']);
                            //open file
                            $handle = fopen($fileToOpen, 'r');
                            //check here to see type of file
                             //Debugger::dump($thisd->data['Upload']['ftype']);                                //REMOVE THIS TO ALLOW FILE TYPE RECOGNITION
                            switch($thisd->data['Upload']['ftype']){
                                case 'application/vnd.ms-excel':
                                    $ext = substr($fileToOpen, strrpos($fileToOpen, '.') + 1);
                                    if($ext == '.csv'){
                                        //.csv
                                        $nn = 0;
                                        while (($data1 = fgetcsv($handle, 1000, ",")) !== FALSE) {

                                            # Count the total keys in the row.
                                            $c = count($data1);
                                            # Populate the multidimensional array.
                                            for ($x=0;$x<$c;$x++)
                                            {
                                                $data[$nn][$x] = $data1[$x];
                                            }
                                            $nn++;
                                        }
                                    }else{
                                        //xls
                                        //Debugger::dump($fileToOpen);
                                        $data = new Spreadsheet_Excel_Reader($fileToOpen, true);
                                        //Debugger::dump($data
                                        //        );
                                        $data = $data->dumptoarray();
                                        //Debugger::dump($data);

                                    }
                                    
                                break;
                                default:
                                    $lines = file($fileToOpen);                                           //REMOVE THIS TO ALLOW FILE TYPE RECOGNITION
                                    //Semicolon delimited
                                    //
                                    //
                                    //CUSTOM AREA FOR TAXDATABASE!!!!!!!
                                    //since the N155 Files have no way to identify the company I must catch it as it parses
                                    //the lines.
                                    //check for TAX exception
                                //debugger::dump($N155);
                                    switch($N155){
                                        
                                        case 'N155':
                                            $account = '';
                                            $companyRowName = '';
                                        //reset the company row
                                        $companyRowName = '';
                                        //go through each line of our accumulated file
                                        foreach ($lines as $line_num => $line) {
                                            if($line_num >= 0) {
                                                //use function to create array with correct file format
                                                $formatted_line = $this->format_line($line);

                                                //check line if new company
                                                /**if(strpos($formatted_line[0],'LSNPA064-') === false){
                                                    //dont set new co
                                                }else{
                                                    //set new company
                                                    $companyRowName = substr($line,9,2);
                                                }
                                                 *
                                                 */
                                                if(strpos($line,'company')===false){
                                                   
                                                }else{                                                     
                                                    $companyRowName = substr($line,8,2);                                                    
                                                }

                                                if(strpos($line,'            ACCOUNT DETAIL FOR')===false){
                                                    
                                                }else{
                                                    $account = substr($line,73,7);
                                                    
                                                }
                                                //add new company to array for import table
                                                
                                                $formatted_line[] = $companyRowName;
                                                $formatted_line[] = $account;
                                                $data[$line_num] = $formatted_line;
                                                //debugger::dump($formatted_line);
                                            }
                                        }
                                    
                                    break;
                                  
                                    default:
                                        $chr = ";";
                                        //for each line, explode the characters into an array for the table
                                        foreach ($lines as $line_num => $line) {
                                            
                                            if($line_num >= 1) {
                                              
                                                $finalArray = array();
                                                $finalArray[] = substr($line,0,2);
                                                $finalArray2 = explode($chr, $line);
                                                $finalArray = array_merge($finalArray,$finalArray2);
                                                //debugger::dump($finalArray);
                                                $data[$line_num] = $finalArray;
                                             
                                            }
                                        }

                                    }
        
                                              //end CUSTOM AREA FOR TAXDATABASE
                                       //debugger::dump($data);
                                break;
                        }
                            //END SWITCH
                                                                                 //REMOVE THIS TO ALLOW FILE TYPE RECOGNITION

                            //OPTIONAL DEFINE FLDS
                            $importTableName = ucfirst(strtolower($thisd->data['Upload']['model']))."import";
                            //Debugger::dump($importTableName);
                            //get Number of FLDS in Data
                            $totImportFlds = count($data[0]);
                            
                            //get number of flds in Table
                            //OLD WAY >> $totFields = count($fldArray);
                            $totFields = count($thisd->$importTableName->_schema);             //UNCOMMMENT THIS FOR GETTING VARIABLE MODEL FORMAT
                            //debugger::dump($totFields);
//make sure data matches table format
                            //Debugger::dump($totImportFlds);
                            //Debugger::dump($totFields);
                            if($totImportFlds == $totFields){

                                //go through each data line
                                for($datalinearray=1;$datalinearray<count($data)+1;$datalinearray++){
                                    //Debugger::dump($data[$datalinearray]);
                                    $sqlstrFld = '';
                                    $sqlstrVal = '';
                                    //get table field names and types
                                    $sqlstr = 'INSERT INTO '.strtolower($importTableName).'s ';                                              //UNCOMMMENT THIS FOR GETTING VARIABLE MODEL FORMAT
                                    $testarray = $thisd->$importTableName->_schema;
                                    $j = 0;
                                    foreach($testarray as $key => $value){
                                        $sqlstrFld = $sqlstrFld.$key.',';
                                        //Debugger::dump($sqlstrFld);
                                        //Debugger::dump($thisd->$importTableName->_schema[$key]);
                                         switch($thisd->$importTableName->_schema[$key]['type']){
                                            case 'integer':
                                            case 'float':
                                            case 'decimal':


                                            case 'boolean':
                                                //Debugger::dump($data[$datalinearray][$j]);
                                                if(strlen($data[$datalinearray][$j])==0 || $data[$datalinearray][$j]='' || trim($data[$datalinearray][$j])==='' || empty($data[$datalinearray][$j]) || is_null($data[$datalinearray][$j]) || !isset($data[$datalinearray][$j])){
                                                    $sqlstrVal = $sqlstrVal.'1,';
                                                }else{
                                                    $sqlstrVal = $sqlstrVal.$data[$datalinearray][$j].",";
                                                }
                                            break;
                                            case 'date':
                                                //check for date with slashes
                                                if(strpos($data[$datalinearray][$j],'/') === false){
                                                   
                                                     
                                                }else{
                                                     //mm/dd/yyyy
                                                   $modifiedDataLineArray = date("Y-m-d", strtotime($data[$datalinearray][$j]));
                                                   $sqlstrVal = $sqlstrVal."'".$modifiedDataLineArray."',";

                                                }
                                            break;
                                            default:
                                                //debugger::dump($data[$datalinearray]);
                                                $sqlstrVal = $sqlstrVal."'".sanitize::clean($data[$datalinearray][$j])."',";
                                            break;
                                        }
                                        $j = $j + 1;
                                        //Debugger::dump($sqlstrVal);
                                    }
                                    //do completed query
                                    $sqlstrFld = '('.substr($sqlstrFld,0,-1).') VALUES ';
                                    if(substr($sqlstrVal,0,-2)==",'"){
                                        $sqlstrVal = '('.substr($sqlstrVal,0,-2)."'".')';
                                    }else{
                                        $sqlstrVal = '('.substr($sqlstrVal,0,-1).')';
                                    }
                                    //Debugger::dump($sqlstr.$sqlstrFld.$sqlstrVal);
                                    
                                    $this->query($sqlstr.$sqlstrFld.$sqlstrVal);

                                }
                            }
                        fclose($handle);
                    }
                    return count($data);
                }
        /**
         *
         * @param <type> $line
         * @return <type> array
         * This function defines the N155 file format.
         */
        Public function format_line($line){
            //start, length, skip.
            //company is handled above (0,2)
            $fileformat[] = trim(substr($line,0,11));
            //$fileformat[] = substr(11,6,true);
            $fileformat[] = trim(substr($line,17,11));
            //$fileformat[] = substr(28,6,true);
            $fileformat[] = trim(substr($line,34,11));
            //$fileformat[] = substr(45,6,true);
            $fileformat[] = trim(substr($line,51,9));
            //$fileformat[] = substr(60,8,true);
            $fileformat[] = trim(substr($line,68,4));
            //$fileformat[] = substr(72,7,true);
            $fileformat[] = trim(substr($line,79,11));
            //$fileformat[] = substr(90,7,true);
            $fileformat[] = trim(substr($line,97,11));
            //$fileformat[] = substr(108,3, true);
            $fileformat[] = trim(substr($line,111,3));
            //$fileformat[] = substr(114,7,true);
            $fileformat[] = trim(substr($line,121));

            return $fileformat;

        }

        Public function count_days( $a, $b ){
            // First we need to break these dates into their constituent parts:
            $gd_a =  $a;
            $gd_b =  $b;

            // Now recreate these timestamps, based upon noon on each day
            // The specific time doesn't matter but it must be the same each day
            $a_new = mktime( 12, 0, 0, $gd_a['month'], $gd_a['day'], $gd_a['year'] );
            $b_new = mktime( 12, 0, 0, $gd_b['month'], $gd_b['day'], $gd_b['year'] );

            // Subtract these two numbers and divide by the number of seconds in a
            //  day. Round the result since crossing over a daylight savings time
            //  barrier will cause this time to be off by an hour or two.
            return round( abs( $a_new - $b_new ) / 86400 );
        }

        Public Function sendEmails($to, $from, $subject, $cc, $bcc, $copyAdmin, $template='test', $layout ='test', $sendas='html'){
            $this->Email->replyTo = 'noreply@domain.com';
            $this->Email->from    = 'System <noreply@domain.com>';
            $this->Email->to      = $to;
            $this->Email->cc      = $cc;
            $this->Email->subject = $subj;

            if ($copyAdmin == true){
                $this->Email->bcc = array('djstearns402@gmail.com',$bcc);
            }else{
                $this->Email->bcc = array($bcc);
            }

            /* Format Options */
            $this->Email->sendas    = $sendas;
            $this->Email->template  = $template;
            $this->Email->layout    = $layout;

             /* SMTP Options */
            $this->Email->smtpOptions = array(
                'host' => 'mail.tagtmi.com'
            );
            /* Set delivery method */
            $this->Email->delivery = 'smtp';
            /* Do not pass any args to send() */
            $this->Email->send();
            /* Check for SMTP errors. */
            $this->set('smtp-errors', $this->Email->smtpError);
        }

        Public function genRandomString($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $string = '';
            for ($p = 0; $p < $length; $p++) {
                $string = $string.$characters[mt_rand(0, strlen($characters)-1)];
            }

            return $string;
        }

        Public function addDays($days, $backwards=false){
            $back = '';
            if($backwards==true){
                $back = '-';
            }else{
                $back = '+';
            }
            $newdate = strtotime ( $back.$days.' days' , strtotime ( date( 'Y-m-j') ) ) ;
            $newdate = date ( 'Y-m-j' , $newdate );

            return $newdate;
        }

        Public function setSessionPage(){

        }
    }
                                   //UNCOMMMENT THIS FOR GETTING VARIABLE MODEL FORMAT
    /*
                                    for($j=0;$j<$totFields;$j++){
                                        $sqlstrFld = $sqlstrFld.$fldArray[$j].',';
                                    }
                                    $sqlstrFld = '('.substr($sqlstrFld,0,-1).') Values ';
                                    //set table to insert
                                    $sqlstr = 'INSERT INTO tasksimports ';
                                    //for each data line (array in master) go through and create SQL string for Values
                                    for($j=0;$j<$totFields;$j++){
                                       
                                        switch($this->Task->_schema[$key]['ftype']){
                                            case 'integer':
                                            case 'float':
                                            case 'decimal':
                                            case 'boolean':
                                                $sqlstrVal = $sqlstrVal.$data[$datalinearray][$j].",";
                                            case 'default':
                                                $sqlstrVal = $sqlstrVal."'".$data[$datalinearray][$j]."',";
                                            break;
                                        }
                                        
                                        $sqlstrVal = $sqlstrVal."'".$data[$datalinearray][$j]."',";
                                    }
                                    //do completed query
                                    $sqlstrVal = '('.substr($sqlstrVal,0,-2)."'".')';
                                    $this->query($sqlstr.$sqlstrFld.$sqlstrVal);
                                }
                                fclose($handle);
                                break;
                            }else{
                                //THE TABLE FROMAT DOESN'T MATCH THE FILE DON'T IMPORT!
                            }

                    }
            }

}
 */

    /*
         function uploadDataOLD($thisd) {
            //Debugger::dump($thisd->name);
            if (!empty($thisd->data)) {
                    //bulk uploader

                    switch($thisd->data['Upload']['model']){
                        case 'task':
                            //get file to open
                            $fileToOpen = (string)$thisd->data['Upload']['fdir'];
                            //open file
                            $handle = fopen($fileToOpen, 'r');

                                    //Semicolon delimited
                                    $lines = file($fileToOpen);
                                    //read the lines
                                    //Debugger::dump($lines);
                                    //dump lines to array
                                    foreach ($lines as $line_num => $line) {
                                                                                    //change this number to START ON LINE
                                        if($line_num >= 1) {
                                                                                    //put LINE conditions HERE!!!
                                            $data[$line_num] = explode(";", $line);
                                            //Debugger::dump($data[$line_num]);
                                            //Debugger::dump($line_num);
                                        }
                                    }


                            //OPTIONAL DEFINE FLDS
                            $fldArray = array('process','step','qdescription','senderopid','policynumber','slaindicator','reportname');
                            //get Number of FLDS in Data
                            $totImportFlds = count($data[1]);
                            //get number of flds in Table
                            $totFields = count($fldArray);
                            //$totFields = count($thisd->Task->_schema);             //UNCOMMMENT THIS FOR GETTING VARIABLE MODEL FORMAT
                            //make sure data matches table format
                            //Debugger::dump($totImportFlds);
                            //Debugger::dump($totFields);
                            if($totImportFlds == $totFields){

                                //go through each data line
                                for($datalinearray=1;$datalinearray<count($data);$datalinearray++){
                                    $sqlstrFld = '';
                                    $sqlstrVal = '';
                                    //get table field names and types
                                    for($j=0;$j<$totFields;$j++){
                                        $sqlstrFld = $sqlstrFld.$fldArray[$j].',';
                                    }
                                    $sqlstrFld = '('.substr($sqlstrFld,0,-1).') Values ';
                                    //set table to insert
                                    $sqlstr = 'INSERT INTO tasksimports ';
                                    //for each data line (array in master) go through and create SQL string for Values
                                    for($j=0;$j<$totFields;$j++){
                                        $sqlstrVal = $sqlstrVal."'".$data[$datalinearray][$j]."',";
                                    }
                                    //do completed query
                                    $sqlstrVal = '('.substr($sqlstrVal,0,-2)."'".')';
                                    $this->query($sqlstr.$sqlstrFld.$sqlstrVal);
                                }
                                fclose($handle);
                                break;
                            }else{
                                //THE TABLE FROMAT DOESN'T MATCH THE FILE DON'T IMPORT!
                            }

                    }
            }
        }


*/
?>
