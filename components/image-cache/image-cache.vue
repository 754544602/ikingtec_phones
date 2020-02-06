<template>
	<image :src="img_url" :mode="mode" :lazy-load="lazyLoad" :style="setStyle"></image>
</template>

<script>
export default {
	name: 'image-cache',
    props: {
    	mode: {
    		type: String,
    		default: ''
    	},
        lazyLoad:{
            type:Boolean,
            default:false
        },
        imgSrc:{
            type:String
        },
        defaultImg:{
            type:String,
            default:''
        },
        setStyle:{
            type:String,
            default:''
        }
    },
	data() {
		return {
            img_url:''
		}
	},
    watch:{
        img_url:{
            handler:function(){
                var the = this;
                uni.getStorage({
                    key:the.imgSrc,
                    success:function(res){
                        if(res.data!=''){
                            the.img_url = res.data;
                        }else{
                            uni.downloadFile({
                                url:the.imgSrc,
                                success: (ress) => {
                                    if (ress.statusCode === 200) {
                                        uni.setStorage({
                                            key:the.imgSrc,
                                            data:ress.tempFilePath,
                                            success:function(){
                                                
                                            }
                                        })
                                        the.img_url = ress.tempFilePath;
                                    }else{
                                        the.img_url = the.defaultImg;
                                    }
                                },
                                fail:()=>{
                                    the.img_url = the.defaultImg;
                                }
                            }); 
                        }
                    },
                    fail:function(res){
                        uni.downloadFile({
                            url:the.imgSrc,
                            success: (ress) => {
                                if (ress.statusCode === 200) {
                                    uni.setStorage({
                                        key:the.imgSrc,
                                        data:ress.tempFilePath,
                                        success:function(){
                                            
                                        }
                                    })
                                    the.img_url = ress.tempFilePath;
                                }else{
                                    the.img_url = the.defaultImg;
                                }
                            },
                            fail:()=>{
                                the.img_url = the.defaultImg;
                            }
                        }); 
                    }
                })
                
            },
            immediate: true
        }

    }
}
</script>