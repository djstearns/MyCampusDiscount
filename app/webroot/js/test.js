function addElement(div){
  var divToAddTo = document.getElementById(div);
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = "Element Number "+num+" has been added! <a href='javascript:;' onclick='removeElement("+divIdName+")'>Remove the div "+divIdName+"</a>";
  divToAddTo.appendChild(newdiv);

}

function removeElement(divNum) {
    alert('test');
  var d = document.getElementById('ftypearea');
  var olddiv = document.getElementById(divNum);
  d.removeChild(olddiv);
}