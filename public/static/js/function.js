
var  heredoc = function (fn) {
  return fn.toString().replace(/^[^\/]+\/\*!?\s/,'').replace(/\*\/[^\/]+$/,'')
};
Array.prototype.contains = function ( needle ) {
    for (i in this) {
      if (this[i] === needle) return true;
    }
    return false;
};
Array.prototype.shuffle = function() {
	var m = this.length, i;
	while (m) {
	  i = (Math.random() * m--) >>> 0;
	  [this[m], this[i]] = [this[i], this[m]]
	}
	return this;
};
Array.prototype.remove=function(obj){
	for(var i =0;i <this.length;i++){
	  var temp = this[i];
	  if(!isNaN(obj)){
	    temp=i;
	  }
	  if(temp == obj){
	    for(var j = i;j <this.length;j++){
	      this[j]=this[j+1];
	    }
	    this.length = this.length-1;
	  }
	}
};
var getFileType = function (upFileName) {
    var index1=upFileName.lastIndexOf(".");
    var index2=upFileName.length;
    var suffix=upFileName.substring(index1+1,index2);
	return suffix;
};


var GetQueryString = function (name){
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}