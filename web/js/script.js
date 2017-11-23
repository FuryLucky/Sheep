i = 0;
j = 0;

function divVisible(){
	if (i === 0) {
		document.getElementById('formVisibility').style.cssText = 'visibility: visible;';
		i++;
	}else{
		document.getElementById('formVisibility').style.cssText = 'visibility: hidden;';
		i--;
		document.getElementById('formUsers').style.cssText = 'visibility: hidden;';
		j=0;
	}
}
function usersVisible(){
	if (j === 0) {
		document.getElementById('formUsers').style.cssText = 'visibility: visible;';
		j++;
	}else{
		document.getElementById('formUsers').style.cssText = 'visibility: hidden;';
		j--;
	}
}