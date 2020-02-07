<template>
	<view class="content">
		<view class="type_content">
			<button class="primary" :class="[action==1?'action':'']" @click="active(1)">时效性任务</button>
			<button class="primary" :class="[action==2?'action':'']" @click="active(2)">周期任务</button>
			<button class="primary" :class="[action==3?'action':'']" @click="active(3)" style="color: #e60012;">紧急任务</button>
		</view>
		<view class="task_content">
			<view v-if="action==1">
				<span class="title blue">开始时间</span>
				<button class="primary blue date" @click="open">{{dateInfo.date}} {{dateInfo.time}}</button>
			</view>
			<view v-if="action==2">
				<view class="iking-flex-left">
					<span class="title blue">巡检周期</span>
					<picker :range="cycleTypeList" :value="cycleTypeIndex" @change="changeCycleType" class="pick1">
						<iking-select class="cycleType">
							{{cycleTypeList[cycleTypeIndex]}}
						</iking-select>
					</picker>
				</view>
				<view v-for="(item,index) in cycleList" class="cycleContent">
					<span class="title blue" style="margin-right: 40upx;">巡检时间</span>
					<picker :range="cycleDateList" :value="item.date" @change="changeDate($event,index)" class="pick1" v-show="cycleDateList.length>0">
						<iking-select>
							{{item.dateSpan}}
						</iking-select>
					</picker>
					<picker mode="time" :value="item.time" :key="index" @change="selectTime($event,index)">
						<iking-select>
							{{item.time.split(':')[0]}}
						</iking-select>
						<span>:</span>
						<iking-select>
							{{item.time.split(':')[1]}}
						</iking-select>
					</picker>
					<view class="addCycle" v-if="index==0" @click="addcycle(index)">+</view>
					<view class="delCycle" v-if="index!=0" @click="delcycle(index)">—</view>
				</view>
			</view>
			<view v-if="action==3">
				<span class="title blue">需求无人机数量</span>
				<picker-view :indicator-style="indicatorStyle" :value="[1]" @change="bindChange">
					<picker-view-column>
						<view class="item" v-for="(item,index) in deviceList" :key="index+1">{{index+1}}</view>
					</picker-view-column>
				</picker-view>
			</view>
			<span class="title blue">任务地点</span>
			<map class="map" id="map1" :hidden="mapshow" :latitude="latitude" :longitude="longitude" :markers="covers" @tap="tap"
			 @regionchange="regionchange" :polyline="polyline" :polygons="polygons">
			</map>
			<span class="title blue">任务描述</span>
			<textarea placeholder="请输入" class="textarea" auto-height />
			</view>
		<uni-calendar class="calendar" ref="calendar" :time="dateInfo.time" :date="dateInfo.date" :insert="dateInfo.insert" :lunar="dateInfo.lunar" :startDate="dateInfo.startDate" :endDate="dateInfo.endDate" :range="dateInfo.range" @confirm="confirm" @close="close"/>
		<view class="bottom_content">
			<button class="primary blue" @click="submit">提交</button>
		</view>
	</view>
</template>

<script>
	import uniCalendar from '@/components/uni-calendar/uni-calendar.vue';
	import uniIcon from '@/components/uni-icons/uni-icons.vue'
	import ikingSelect from '@/components/iking-select.vue'
	function changeDate(e,index){
		console.log(e,index)
	}
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
			uniCalendar,
			uniIcon,
			ikingSelect
		},
		onLoad() {
			var me = this;
			this.map = uni.createMapContext("map1",this);
			console.log(this.map.getCenterLocation());
			uni.getLocation({
				type:'gcj02',
				success:function(e){
					console.log(e)
					me.latitude = e.latitude
					me.longitude = e.longitude
					me.covers[0].longitude = e.longitude;
					me.covers[0].latitude = e.latitude;
				}
			})
			
		},
		data(){
			return {
				action:1,
				date:'',
				mapshow:false,
				map:null,
				deviceNum:'1',
				deviceList:[
					{
						deviceName:"无人机1"
					},
					{
						deviceName:"无人机2"
					},
					{
						deviceName:"无人机3"
					},
					{
						deviceName:"无人机4"
					}
				],
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
				cycleTypeIndex:0,
				cycleTypeList:["每月","每周","每日"],
				cycleList:[{
					time:'09:09',
					date:0,
					dateSpan:"1"
				}],
				cycleDateList:[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
				week:["周一","周二","周三","周四","周五","周六","周日"],
				dateList:[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
				latitude: 39.119929,
				longitude: 117.752705,
				covers: [{
					latitude: 39.9085,
					longitude: 116.39747,
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
				}],
				polygons:{},
				indicatorStyle: `height: ${Math.round(uni.getSystemInfoSync().screenWidth/(750/100))}px;`
			}
		},
		methods:{
			//切换任务类型
			active(num){
				this.action = num
			},
			//map获取坐标
			regionchange(e){
				uni.getCenterLocation({
					success:function(value){
						console.log(value)
					}
				})
			},
			//选择巡检类型 每月，每日，没星期
			changeCycleType(e){
				this.cycleTypeIndex = e.detail.value;
				switch (this.cycleTypeIndex){
					case 0:
					this.cycleDateList = this.dateList
						break;
					case 1:
					this.cycleDateList = this.week
						break;
					case 2:
					this.cycleDateList = []
						break;
					default:
						break;
				}
				this.cycleList.map(value=>{
					value.date =0 ;
					value.dateSpan = this.cycleDateList[value.date]
					return value
				})
			},
			//选择巡检时间日期
			changeDate(e,index){
				this.cycleList[index].date = e.detail.value;
				this.cycleList[index].dateSpan = this.cycleDateList[e.detail.value]
				console.log(this.cycleList[index])
				
			},
			//选择巡检时间时分
			selectTime(e,index){
				this.cycleList[index].time = e.detail.value;
				console.log(e,index)
			},
			//增加巡检时间
			addcycle(index){
				this.cycleList.push({
					time:'09:00',
					date:0,
					dateSpan:this.cycleDateList[0]
				})
			},
			//删除巡检时间
			delcycle(index){
				console.log(index)
				console.log(this.cycleList)
				this.cycleList.splice(index,1)
			},
			tap(e){
				this.polyline[0].points.push({
			        latitude: e.detail.latitude,
			        longitude: e.detail.longitude
			    })
				var me = this
				uni.chooseLocation({
					success:function(e){
						console.log(e)
						me.latitude = e.latitude
						me.longitude = e.longitude
						me.covers[0].longitude = e.longitude;
						me.covers[0].latitude = e.latitude;
					}
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
			},
			bindChange(e){
				this.deviceNum = e.detail.value[0]+1;
				console.log(this.deviceNum)
			},
			submit(){
				uni.showModal({
					title:"是否提交任务？",
					// content: "是否提交任务？",
					confirmText: "确定",
					cancelText: "取消",
					success: function (res) {
						if (res.confirm) {
							console.log('用户点击确定');
							uni.navigateBack();
						} else if (res.cancel) {
							console.log('用户点击取消');
						}
					}
				})
			}
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
	}
		
	.cycleType{
		width: 160upx;
		margin:0 30upx 0 40upx;
	}
	.cycleContent{
		display: flex;
		justify-content: flex-start;
		align-items: center;
		.pick1{
			.mini-btn{
				width: 160upx;
				margin:0 20upx 0 0upx;
			} 
		}
		uni-picker:nth-child(3){
			.mini-btn{
				display: inline-flex;
				&:nth-child(1){
					width: 100upx;
					padding: 0 10upx 0 16upx;
					margin:0 10upx 0 0upx;
				}
				&:nth-child(3){
					width: 100upx;
					padding: 0 10upx 0 16upx;
					margin:0 0upx 0 10upx;
				}
			}
		}
		.addCycle,.delCycle{
			width: 30upx;
			height: 30upx;
			border-radius: 50%;
			background-color: #2ba3ef;
			display: flex;
			color: #fff;
			justify-content: center;
			align-items: center;
			margin-left: 20upx;
		}
		.delCycle{
			background-color: #fa3043;
		}
	}
	.task_content{
		margin-top: 26.8upx;
		background-color: #fff;
		border-radius: 13upx;
		padding:37upx 20upx;
		color: #2ba3ef;
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
	 picker-view {
        width: 100%;
        height: 200upx;
        margin-top:20upx;
		color: #333;
    }
	picker-view-column view{
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>
