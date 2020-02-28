<template>
	<view>
		<scroll-view scroll-y="true" class="scroll_view" @scrolltolower="lower" :scroll-top="scrollTop">
			<!-- <view class="websocket-msg" :scroll-top="scrollTop" v-for="item in message">{{item}}</view> -->
			<view v-for="(item,index) in message" :key="index">
				<view class="iking-flex-left iking-flex-top messageContent" v-if="item.from!='me'">
					<view class="acceptImage">
						<image src="../../static/blackImage.png"></image>
						<!-- <span>{{item.name}}</span> -->
					</view>
					<view class="acceptMessage">
						<p>{{item.msg}}</p>
					</view>
				</view>
				<view class="iking-flex-right iking-flex-top messageContent" v-if="item.from=='me'">
					<view class="sendMessage">
						<p>{{item.msg}}</p>
					</view>
					<view class="sendImage">
						<image src="../../static/headimage.png"></image>
						<!-- <span>{{item.name}}</span> -->
					</view>
				</view>
			</view>
		</scroll-view>
		<view class="bottom_content">
				<input type="text" value="" v-model.trim="sendMsg"  class="messageInput"/>
				<button class="primary blue" @click="sendMessage">发送</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				socketTask:null,
				roomId:'100',
				msg:'',
				message:[],
				sendMsg:'',
				scrollTop:0
			};
		},
		methods: {
			sendMessage() {
				var me = this;
				var data = JSON.stringify({
					name:'TOM',
					msg:me.sendMsg,
					from:'me'
				})
				this.socketTask.send({
					// data: 'from ' + platform + ' : ' + parseInt(Math.random() * 10000).toString(),
					data: data,
					success(res) {
						console.log(res);
						me.message.push(JSON.parse(data));
						me.sendMsg='';
					},
					fail(err) {
						console.log(err);
					}
				})
			},
			lower: function(e) {
				console.log(e)
			},
		},
		onReady() {
			this.socketTask = uni.connectSocket({
				url: 'wss://connect.websocket.in/hello_uni_app?room_id=' + this.roomId,
				data() {
					return {
						msg: 'Hello'
					}
				},
				// #ifdef MP
				header: {
					'content-type': 'application/json'
				},
				// #endif
				// #ifdef MP-WEIXIN
				method: 'GET',
				// #endif
				success(res) {
					console.log("success:"+res)
					// 这里是接口调用成功的回调，不是连接成功的回调，请注意
				},
				fail(err) {
					// 这里是接口调用失败的回调，不是连接失败的回调，请注意
				}
			})
			this.socketTask.onOpen((res) => {
				this.connecting = false
				this.connected = true
				uni.hideLoading()
				uni.showToast({
					icon: 'none',
					title: '连接成功'
				})
				console.log('onOpen', res);
			})
			this.socketTask.onError((err) => {
				this.connecting = false
				this.connected = false
				uni.hideLoading()
				uni.showModal({
					content: '连接失败，可能是websocket服务不可用，请稍后再试',
					showCancel: false
				})
				this.connect();
				console.log('onError', err);
			})
			this.socketTask.onMessage((res) => {
				this.msg = JSON.parse(res.data);
				this.msg.from = 'you';
				this.message.push(this.msg);
				this.scrollTop=(this.message.length+1)*50;
				console.log('onMessage', res)
			})
			this.socketTask.onClose((res) => {
				this.connected = false
				this.startRecive = false
				this.socketTask = false
				this.msg = false
				console.log('onClose', res)
			})
		}
	}
</script>

<style lang="scss">
page{
	background-color: #fff;
	background-image: none;
	color: #333;
}
.bottom_content{
		position: fixed;
		bottom: 0;
		width: 100%;
		height: 112upx;
		border-top: 2upx solid #e5e5e5;
		background-color: #fff;
		box-sizing: border-box;
		display: flex;
		justify-content: center;
		align-items: center;
		.blue{
			width: 120upx;
			height: 73upx;
			font-size: 32upx;
			line-height: 73upx;
			padding: 0;
			margin-left: 24upx;
		}
}
.messageInput{
	width: 561upx;
	height: 73upx;
	background-color: #e5e5e5;
	border-radius: 20upx;
	border: none;
	color: #333;
	line-height: 73upx;
	padding: 0 10upx;
	box-sizing: border-box;
}
.acceptImage,.sendImage{
	width: 114.5upx;
	height: 114.5upx;
	border-radius: 50%;
	border: solid 3upx #b5b5b5;
	position: relative;
	uni-image{
		width: 100%;
		height: 100%;
	}
}
.acceptMessage{
	width: 496upx;
	background-color: #ffffff;
	border-radius: 7upx;
	border: solid 3upx #b5b5b5;
	margin-left: 47upx;
	padding: 30upx;
	box-sizing: border-box;
	word-break: break-word;
}
.sendMessage{
	width: 496upx;
	background-color: #1d94df;
	border-radius: 7upx;
	border: solid 3upx #b5b5b5;
	color: #fff;
	margin-right: 47upx;
	padding: 30upx;
	box-sizing: border-box;
	word-break: break-word;
}
.scroll_view{
	padding: 0 40upx;
	box-sizing: border-box;
	height: 100%;
	padding-bottom: 130upx;
}
.messageContent{
	margin-top: 50upx;
}
</style>
