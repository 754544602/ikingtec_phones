<template>
	<view class="content">
		<view class="selectDeviceView">
			<picker ref="pick" :value="selectDevice" :range="deviceList" @change="changeDevice">
				<iking-select>
					{{deviceList[selectDevice].label}}
				</iking-select>
			</picker>
		</view>
		<view class="liveView">
			<video class="livePlayer" :src="liveSrc" id="myVideo" autoplay></video>
			<span class="liveSpan">实时视频</span>
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
		<!-- <cover-view class="selectDeviceView">
			{{deviceList[selectDevice].label}}
			<uni-icons type="arrowdown" size="20" color="#ffffff"></uni-icons>
		</cover-view> -->
	</view>
</template>
<script>
	import ikingSelect from '@/components/iking-select.vue'
import mpvuePicker from '@/components/mpvue-picker/mpvuePicker.vue';
	import uniIcons from '@/components/uni-icons/uni-icons.vue'
	export default {
		components: {
			ikingSelect,
			mpvuePicker,
			uniIcons
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
	}
	.livePlayer{
		border: 6upx solid #1d94df;
		border-radius: 6upx;
		width: 100%;
		height: 100%;
	}
	.liveSpan{
		position: absolute;	
		top: 0;
		left: 55upx;
		color: #fff;
	}
</style>
