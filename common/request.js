let request = {}
import config from './config.js';
request.post = (url,data)=>{
	if(!data)data={}
	if(uni.getStorageSync('login_info')){
		data.author = JSON.parse(uni.getStorageSync('login_info')).username;
		data.authorType = JSON.parse(uni.getStorageSync('login_info')).userType;
	}
	console.log(url,data,uni.getStorageSync('login_info'));
	uni.showLoading()
	return uni.request({
		url:config.basePath+url,
		data:data,
		method:"POST",
		dataType:'json',
		sslVerify: false
	}).then((res)=>{
		// console.log(JSON.stringify(res))
		uni.hideLoading()
		return res[1].data
	}).catch((e)=>{
		console.log(e)
		uni.hideLoading()
		uni.showToast({
			title:"网络异常",
			icon:'none'
		})
	})
}
request.uploadFile = (url,files,data) =>{
	uni.showLoading();
	return uni.uploadFile({
		url: config.basePath + url, //仅为示例，非真实的接口地址
		files: files,
		name: 'file',
		formData: data,
		
	}).then(res=>{
		uni.hideLoading();
		return JSON.parse(res[1].data)
	}).catch(e=>{
		uni.hideLoading();
		console.log(e);
		uni.showToast({
			title: '上传失败'
		});
	})
}
request.get = ()=>{
	
}
module.exports = request