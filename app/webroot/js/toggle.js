function addElement(div, divtocopy){

  var divToAddTo = document.getElementById(div);
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = num;
  newdiv.setAttribute('id',divIdName);
  //newdiv.innerHTML = 'Element Number '+num+' has been added! <a href="javascript:;" onclick=\"removeElement(\''+divIdName+'\');">Remove the div "'+divIdName+'"</a>';
  var d = document.getElementById(divtocopy);


  //newdiv.innerHTML = d.innerHTML;//+'<div style="float:right;"><a href="javascript:;" onclick=\"removeElement(\''+divIdName+'\');">Remove</a></div>';
  newdiv.innerHTML = d.innerHTML.replace(/[0]/g,num);
  newdiv.innerHTML = d.innerHTML.replace("flds-name","flds-name"+num);

    var rdiv = document.createElement('div');
    rdiv.setAttribute('id','remove');
    rdiv.style.cssFloat = "right";
    rdiv.style.styleFloat = "right";
      
    rdiv.innerHTML='<a href="javascript:;" onclick=\"removeElement(\''+divIdName+'\');">Remove</a>';

    var clrdiv = document.createElement('div');
    clrdiv.setAttribute('id','clr'+num);
    clrdiv.style.clear = "both";
    
    divToAddTo.appendChild(newdiv);
    newdiv.appendChild(rdiv);
    divToAddTo.appendChild(clrdiv);
    
    

  
 
}



function removeElement(divNum) {
  var d = document.getElementById('etypearea');
  var olddiv = document.getElementById(divNum);
  d.removeChild(olddiv);
  var d = document.getElementById('etypearea');
  var oldrdiv = document.getElementById('clr'+divNum);
  d.removeChild(oldrdiv);
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -2)+ 1;
  numi.value = num;
}