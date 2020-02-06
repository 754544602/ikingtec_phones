<template>
	<view class="content">
		<map class="map" :latitude="latitude" :longitude="longitude">
		</map>
		<view class="selectDeviceView">
			<picker :value="selectDevice" :range="deviceList" @change="changeDevice">
				<iking-select>
					{{deviceList[selectDevice]}}
				</iking-select>
			</picker>
		</view>
		<view class="liveView">
			<video class="livePlayer" :src="liveSrc" id="myVideo" autoplay ></video>
			<span class="liveSpan">实时视频</span>
		</view>
	</view>
</template>
<script>
	import ikingSelect from '@/components/iking-select.vue'

	export default {
		components: {
			ikingSelect
		},
		data() {
			return {
				email: '',
				latitude: 39.119929,
				longitude: 117.752705,
				videoContext:null,
				deviceList: [
					'无人机1',
					'无人机2',
					'无人机3',
					'无人机4'
				],
				selectDevice: 0,
				liveSrc:'rtmp://47.106.185.144/live/dock_camera_top_20000003'
			}
		},
		methods: {
			changeDevice(e) {
				this.selectDevice = e.detail.value;
			}
		},
		onReady() {
			this.videoContext = uni.createVideoContext('myVideo')
			this.videoContext.play();
			new plus.video.VideoPlayer('video',{
						src:'rtmp://47.106.185.144/live/dock_camera_top_20000003'  
			}); 
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
	}

	.selectDeviceView {
		position: absolute;
		bottom: 44upx;
		left: 32upx;
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
