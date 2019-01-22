// Minified using https://jscompress.com/
(function () {
	window.onload = function(){
		var form = document.getElementById('password');
		if (!form)
			return;//No action needed
		var silb = form.parentNode.nextSibling;
		var form = silb.parentNode;
		var div = document.createElement("div");
		form.insertBefore(div, silb);
		setTimeout(function () {
			grecaptcha.render(div, {
				'sitekey': pk
			});
    }, 200);
	};
})();
