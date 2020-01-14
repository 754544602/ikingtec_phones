<template>
	<view class="content">
		<view class="type_content">
			<button class="primary" :class="[action==1?'action':'']" @click="active(1)">时效性任务</button>
			<button class="primary" :class="[action==2?'action':'']" @click="active(2)">周期任务</button>
			<button class="primary" :class="[action==3?'action':'']" @click="active(3)">紧急任务</button>
		</view>
		<view class="task_content">
			<span class="title blue">开始时间</span>
			<button class="primary blue date" @click="open">{{dateInfo.date}} {{dateInfo.time}}</button>
			<span class="title blue">任务地点</span>
			<map class="map" :hidden="mapshow" :latitude="latitude" :longitude="longitude" :markers="covers" @tap="tap" :polyline="polyline" :polygons="polygons">
			</map>
			<span class="title blue">任务描述</span>
			<textarea placeholder="请输入" class="textarea" auto-height/>
		</view>
		<uni-calendar class="calendar" ref="calendar" :time="dateInfo.time" :date="dateInfo.date" :insert="dateInfo.insert" :lunar="dateInfo.lunar" :startDate="dateInfo.startDate" :endDate="dateInfo.endDate" :range="dateInfo.range" @confirm="confirm" @close="close"/>
		<view class="bottom_content">
			<button class="primary blue">提交</button>
		</view>
	</view>
</template>

<script>
	import uniCalendar from '@/components/uni-calendar/uni-calendar.vue';
	function nowDate(){
		let nowDate = new Date();
		return nowDate.getFullYear() + '-' + ('0'+(nowDate.getMonth() + 1)).slice(-2) + '-' + ('0'+nowDate.getDate()).slice(-2);
	}
	function nowTime(){
		let nowDate = new Date();
		return ('0'+nowDate.getHours()).slice(-2)+':'+ ('0'+nowDate.getMinutes()).slice(-2);
	}
	export default {
		components: {
			uniCalendar
		},
		data(){
			return {
				action:1,
				date:'',
				mapshow:false,
				dateInfo: {
					date: nowDate(),
					startDate: nowDate(),
					time:nowTime(),
					endDate: '',
					lunar: false,
					range: false,
					insert: false,
					selected: []
				},
				latitude: 39.909,
					longitude: 116.39742,
					covers: [{
						latitude: 39.9085,
						longitude: 116.39747,
						// #ifdef APP-PLUS
						iconPath: '../../../static/app-plus/location@3x.png',
						// #endif
						// #ifndef APP-PLUS
						iconPath: '../../../static/location.png',
						// #endif
					}, {
						latitude: 39.90,
						longitude: 116.39,
						// #ifdef APP-PLUS
						iconPath: '../../../static/app-plus/location@3x.png',
						// #endif
						// #ifndef APP-PLUS
						iconPath: '../../../static/location.png',
						// #endif
					}],
				polyline:[{
						points: [{
							latitude: 39.9085,
							longitude: 116.39747,
						},{
							latitude: 39.90,
							longitude: 116.39,
						}
						],
						color: '#CCFFFF',
						width: 5,
						dottedLine: true,
						arrowLine: true,
						borderColor: '#CC0000',
						borderWidth: 3
					}
				],
				polygons:{
				
				}
			}
		},
		methods:{
			active(num){
				this.action = num
			},
			tap(e){
				this.polyline[0].points.push({
			        latitude: e.detail.latitude,
			        longitude: e.detail.longitude
			    })
			},
			confirm(e) {
				console.log('confirm 返回:', e)
				this.dateInfo.date = e.fulldate;
				this.dateInfo.time = e.time
				this.mapshow=false;
			},
			close(){
				this.mapshow=false;
			},
			open() {
				this.$refs.calendar.open();
				this.mapshow=true;
			}
		},
		onLoad(){
			
		}
	}
</script>

<style lang="scss">
	.content{
		padding: 0 20upx 112upx 20upx;
	}
	.type_content{
		display: flex;
		justify-content: space-between;
		align-items: center;
		width: 100%;
		margin-top: 51upx;
		button{
			width:208upx;
			padding:27upx 24upx;
			font-size: 32upx;
		}
	}
	.primary{
		&:after{
			border: none;
		}
		&.action{
			color:$iking-color-common;
			background-color: $iking-color-blue;
			box-shadow: 1upx 2px 6upx 0.5upx rgba(0, 0, 0, 0.43);
		}
		&.blue.date{
			width: 100%;
			font-size: 32upx;
			margin-top: 33upx;
			margin-bottom: 41upx;
		}
	}
	.task_content{
		margin-top: 26.8upx;
		background-color: #fff;
		border-radius: 13upx;
		padding:37upx 20upx;
	}
	.map{
		width: 100%;
		height: 380upx;
		margin-top: 42upx;
		margin-bottom: 33upx;
	}
	.textarea{
		margin-top: 33upx;
	}
	.bottom_content{
		position: fixed;
		bottom: 0;
		width: 100%;
		height: 112upx;
		border-top: 2upx solid #e5e5e5;
		background-color: #fff;
		margin-left:-22.5upx;
		box-sizing: content-box;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.calendar{
		z-index: 10;
	}
</style>
