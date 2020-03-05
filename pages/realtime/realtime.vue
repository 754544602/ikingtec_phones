<template>
	<view class="content">
		<view class="selectDeviceView" style="position: fixed;">
			<picker ref="pick" :value="selectDevice" :range="deviceList" @change="changeDevice">
				<iking-select>
					{{deviceList[selectDevice].label}}
				</iking-select>
			</picker>
		</view>
		<view class="liveView">
				<video class="livePlayer" :src="liveSrc" id="myVideo" autoplay>
					<!-- <cover-view class="livePlayerView">
					</cover-view> -->
				</video>
			<cover-view class="liveSpan">实时视频</cover-view>
		</view>
		<mpvue-picker
			ref="mpvuePicker"
			:deepLength="1"
			@onConfirm="onConfirm"
			@onCancel="onCancel"
			:pickerValueArray="deviceList"
		></mpvue-picker>
		<map class="map" id="map" :latitude="latitude" :longitude="longitude">
		</map>
		<cover-view class="uni-list">
			<cell>
			<cover-view class="uni-list-item">
				<cover-view  class="uni-list-item__container">
					
				<cover-view class="uni-list-item__content">
					<cover-view class="uni-list-item__content-title">23123123</cover-view>
					</cover-view>
					</cover-view>
			</cover-view>
			</cell>
		</cover-view>
		<cover-view class="selectDeviceView" style="border: #333333 solid 2upx;">
			{{deviceList[selectDevice].label}}
			<uni-icons type="arrowdown" size="20" color="#ffffff"></uni-icons>
		</cover-view>
	</view>
</template>
<script>
	import ikingSelect from '@/components/iking-select.vue'
import mpvuePicker from '@/components/mpvue-picker/mpvuePicker.vue';
	import uniIcons from '@/components/uni-icons/uni-icons.vue'
	import uniDrawer from '@/components/uni-drawer/uni-drawer.vue'
	import uniList from '@/components/uni-list/uni-list.vue'
	import uniListItem from '@/components/uni-list-item/uni-list-item.vue'
	export default {
		components: {
			ikingSelect,
			mpvuePicker,
			uniIcons,
			uniDrawer,
			uniList,
			uniListItem
		},
		data() {
			return {
				email: '',
				latitude: 39.119929,
				longitude: 117.752705,
				videoContext:null,
				deviceList: [
				{
					label: '无人机1',
					value: 110000
				},
				{
					label: '无人机2',
					value: 120000
				},
				{
					label: '无人机3',
					value: 440100
				},
				{
					label: '无人机4',
					value: 440300
				}
			],
				selectDevice: 0,
				liveSrc:'rtmp://47.106.185.144/live/dock_camera_top_20000003'
			}
		},
		methods: {
			statechange(e){
				console.log('statechange:'+e)
			},
			error(e){
				console.log('error:'+e)
			},
			changeDevice(e) {
				this.selectDevice = e.detail.value;
			},
			onConfirm(e) {
				console.log(e.label);
				this.setStyle(0, e.label);
			},
			onCancel(e) {
				console.log(e);
			},
			showSinglePicker() {
				this.$refs.mpvuePicker.showPicker=true;
				console.log()
				this.$refs.mpvuePicker.show();
			},
			/**
			 * 修改导航栏buttons
			 * index[number] 修改的buttons 下标索引，最右边索引为0
			 * text[string] 需要修改的text 内容
			 */
			setStyle(index, text) {
				let pages = getCurrentPages();
				let page = pages[pages.length - 1];
				if (text.length > 3) {
					text = text.substr(0, 3) + '...';
				}
				// #ifdef APP-PLUS
				let currentWebview = page.$getAppWebview();
				let titleNView = currentWebview.getStyle().titleNView;
				// 添加文字过长截取为3个字符，请根据自己业务需求更改
				titleNView.buttons[0].text = text;
				currentWebview.setStyle({
					titleNView: titleNView
				});
				// #endif
				// #ifdef H5
				// h5 临时方案
				document.getElementsByClassName('uni-btn-icon')[1].innerText = text;
				// #endif
			}
		},
		onReady() {
			this.map = uni.createMapContext('map',this)
			this.videoContext = uni.createVideoContext('myVideo')
			this.videoContext.play();
			// #ifdef APP-PLUS
			new plus.video.VideoPlayer('video',{
				src:'rtmp://47.106.185.144/live/dock_camera_top_20000003'  
			}); 
			// #endif
		},
		onBackPress() {
			if (this.$refs.mpvuePicker.showPicker) {
				this.$refs.mpvuePicker.pickerCancel();
				return true;
			}
		},
		onUnload() {
			if (this.$refs.mpvuePicker.showPicker) {
				this.$refs.mpvuePicker.pickerCancel();
			}
		},
		onNavigationBarButtonTap(e) {
			if (e.index === 0) {
				this.showSinglePicker();
			}
		}
	}
</script>
<style lang="scss">
	.content {
		color: #333;
	}

	.map {
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		width: 100%;
		height: 100%;
		z-index: -1;
	}

	.selectDeviceView {
		position: absolute;
		bottom: 44upx;
		// top: 100upx;
		left: 32upx;
		z-index: 1000;
		background: $iking-color-blue;
		border-radius: 5upx;
		color: #fff;
		text-align: center;
	}
	.liveView{
		position: absolute;
		bottom: 136upx;
		width: 100%;
		height: 375upx;
		padding: 0 40upx;
		box-sizing: border-box;
		left: 0;
		.livePlayerView{
			border: 6upx solid #1d94df;
			border-radius: 6upx;
			width: 100%;
			height: 100%;
		}
		.livePlayer{
			width: 100%;
			height: 100%;
		}
	}
	
	.liveSpan{
		position: absolute;	
		top:5upx;
		left: 55upx;
		color: #fff;
	}
	
	.uni-list {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		background-color: #ffffff;
		position: relative;
		flex-direction: column;
		/* border-bottom-color: $uni-border-color; */
		/* border-bottom-style: solid; */
		/* border-bottom-width: 1px; */
	}
	
	/* #ifndef APP-NVUE */
	.uni-list:before {
		height: 0;
	}
	
	.uni-list:after {
		height: 0;
	}
	
	/* #endif */
	.uni-list-item {
			font-size: 32rpx;
			position: relative;
			flex-direction: column;
			justify-content: space-between;
			padding-left: 30rpx;
		}
	
		.uni-list-item--disabled {
			opacity: 0.3;
		}
	
		.uni-list-item--hover {
			background-color: #f1f1f1;
		}
	
		.uni-list-item__container {
			position: relative;
			/* #ifndef APP-NVUE */
			display: flex;
			/* #endif */
			flex-direction: row;
			padding: 24rpx 30rpx;
			padding-left: 0;
			flex: 1;
			position: relative;
			justify-content: space-between;
			align-items: center;
			/* #ifdef APP-PLUS */
			border-top-color: #e5e5e5;
			border-top-style: solid;
			border-top-width: 0.5px;
			/* #endif */
		}
	
		.uni-list-item--first {
			border-top-width: 0px;
		}
	
		/* #ifndef APP-NVUE */
		.uni-list-item__container:after {
			position: absolute;
			top: 0;
			right: 0;
			left: 0;
			height: 1px;
			content: '';
			-webkit-transform: scaleY(.5);
			transform: scaleY(.5);
			background-color: #e5e5e5;
		}
	
		.uni-list-item--first:after {
			height: 0px;
		}
	
		/* #endif */
	
	
	
	
	
		.uni-list-item__content {
			/* #ifndef APP-NVUE */
			display: flex;
			/* #endif */
			flex: 1;
			overflow: hidden;
			flex-direction: column;
			color: #3b4144;
	
		}
	
		.uni-list-item__content-title {
			font-size: 28rpx;
			color: #3b4144;
			overflow: hidden;
		}
	
		.uni-list-item__content-note {
			margin-top: 6rpx;
			color: #999;
			font-size: 24rpx;
			overflow: hidden;
		}
	
		.uni-list-item__extra {
			/* width: 25%;
	*/
			/* #ifndef APP-NVUE */
			display: flex;
			/* #endif */
			flex-direction: row;
			justify-content: flex-end;
			align-items: center;
		}
	
		.uni-list-item__icon {
			margin-right: 18rpx;
			flex-direction: row;
			justify-content: center;
			align-items: center;
		}
	
		.uni-list-item__icon-img {
			height: 52rpx;
			width: 52rpx;
		}
</style>
