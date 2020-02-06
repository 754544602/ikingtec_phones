<template>
    <view class="content">
		<view v-if="hasLogin">
			<view class="iking-flex-left">
				<view class="headImage">
					<image src="../../static/headimage.png"></image>
				</view>
				<view class="headRight">
					<view class="iking-flex-between">
						<span class="realName">姓名</span>
						<span class="changeInfo">修改</span>
					</view>
					<p class="desc">
						描述描述描述
						描述描述描述
						描述描述描述
					</p>
				</view>
			</view>
			<view class="middle">
				<view class="span-view">
					<span>电话：</span><span>15256632589</span>
				</view>
				<view class="span-view">
					<span>部门：</span><span>交管部门</span>
				</view>
				<view class="span-view">
					<span>职位：</span><span>交警</span>
				</view>
			</view>
		</view>
        <view class="btn-row">
            <button v-if="!hasLogin" type="primary" class="primary" @tap="bindLogin">登录</button>
            <button v-if="hasLogin" type="default" @tap="bindLogout">退出登录</button>
        </view>
    </view>
</template>

<script>
    import {
        mapState,
        mapMutations
    } from 'vuex'

    export default {
        computed: {
            ...mapState(['hasLogin', 'forcedLogin'])
        },
        methods: {
            ...mapMutations(['logout']),
            bindLogin() {
                uni.navigateTo({
                    url: '../login/login',
                });
            },
            bindLogout() {
                this.logout();
                /**
                 * 如果需要强制登录跳转回登录页面
                 */
                if (this.forcedLogin) {
                    uni.reLaunch({
                        url: '../login/login',
                    });
                }
            }
        },
		onLoad(){
			console.log(this.hasLogin,this.$store.state.hasLogin)
		}
    }
</script>

<style lang="scss">
.content{
	padding: 49upx 35upx;
}
.headImage{
	width: 371upx;
	height: 270upx;
	uni-image{
		width: 100%;
		height: 100%;
	}
}
.headRight{
	width: 240upx;
	height: 100%;
	padding-left: 60upx;
}
.realName{
	font-size: 40upx;	color: #fff;
}
.changeInfo{
	font-size: 27upx;
	color: #333;
}
.desc{
	font-size: 24upx;
	color: #fff;
	margin-top: 44upx;
}
.middle{
	margin-top: 57upx;
}
.span-view{
	width: 100%;
	height:86upx;
	line-height: 86upx;
	color: #fff;
	background-color: #2ba3ef;
	border-radius: 10upx;
	padding-left: 32upx;
	box-sizing: border-box;
	margin-bottom: 30upx;
}
</style>
