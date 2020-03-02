<template>
	<view class="content">
		<view class="type_content">
			<button class="primary" :class="[action == 1 ? 'action' : '']" @click="active(1)" v-if="userType != 'dronePilot'">时效性任务</button>
			<button class="primary" :class="[action == 2 ? 'action' : '']" @click="active(2)" v-if="userType != 'dronePilot'">周期任务</button>
			<button class="primary red" :class="[action == 3 ? 'action' : '']" @click="active(3)">紧急任务</button>
		</view>
		<scroll-view class="task_content" scroll-y>
			<view v-if="action == 1">
				<span class="title blue">开始时间</span>
				<button class="primary blue date" @click="open">{{ dateInfo.date }} {{ dateInfo.time }}</button>
			</view>
			<view v-if="action == 2">
				<view class="iking-flex-left">
					<span class="title blue">巡检周期</span>
					<picker :range="cycleTypeList" :value="cycleTypeIndex" @change="changeCycleType" class="cycleType">
						<iking-select>{{ cycleTypeList[cycleTypeIndex] }}</iking-select>
					</picker>
				</view>
				<view v-for="(item, index) in cycleList" :key="index" class="cycleContent">
					<span class="title blue" style="margin-right: 40upx;">巡检时间</span>
					<picker :range="cycleDateList" :value="item.date" @change="changeDate($event, index)" class="pick1" v-if="item.dateSpan">
						<iking-select>{{ item.dateSpan }}</iking-select>
					</picker>
					<picker mode="time" :value="item.time" :key="index" @change="selectTime($event, index)" class="pick2">
						<iking-select name="ikingSelect1">{{ item.time.split(':')[0] }}</iking-select>
						<span>:</span>
						<iking-select name="ikingSelect2">{{ item.time.split(':')[1] }}</iking-select>
					</picker>
					<view class="addCycle" v-if="index == 0" @click="addcycle(index)">+</view>
					<view class="delCycle" v-if="index != 0" @click="delcycle(index)">—</view>
				</view>
			</view>
			<view v-if="action == 3">
				<span class="title blue">需求无人机数量</span>
				<picker-view :indicator-style="indicatorStyle" :value="[1]" @change="bindChange">
					<picker-view-column>
						<view class="item" v-for="(item, index) in deviceList" :key="index">{{ index + 1 }}</view>
					</picker-view-column>
				</picker-view>
			</view>
			<span class="title blue">任务地点</span>
			<map
				class="map"
				id="map1"
				:hidden="mapshow"
				:latitude="latitude"
				:longitude="longitude"
				:markers="covers"
				@tap="tap"
				@regionchange="regionchange"
				:polyline="polyline"
				:polygons="polygons"
			></map>
			<span class="title blue">任务描述</span>
			<textarea placeholder="请输入" class="textarea" auto-height v-model="taskDesc" />
			<form v-if="userType != 'dronePilot' && action == 2">
				<view class="iking-flex-left">
					<span class="title blue">任务标准</span>
					<button type="primary" size="mini" class="mini-btn" @click="uploadImage" style="background-color: #1d94df">提交</button>
				</view>
				<view class="uni-list list-pd">
					<view class="uni-list-cell cell-pd">
						<view class="uni-uploader">
							<view class="uni-uploader-head">
								<view class="uni-uploader-title">点击可预览选好的图片</view>
								<view class="uni-uploader-info">{{ imageList.length }}/9</view>
							</view>
							<view class="uni-uploader-body">
								<view class="uni-uploader__files">
									<block v-for="(image, index) in imageList" :key="index">
										<view class="uni-uploader__file"><image class="uni-uploader__img" :src="image" :data-src="image" @tap="previewImage"></image></view>
									</block>
									<view class="uni-uploader__input-box"><view class="uni-uploader__input" @tap="chooseImage"></view></view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</form>
		</scroll-view>
		<uni-calendar
			class="calendar"
			ref="calendar"
			type="time"
			:time="dateInfo.time"
			:date="dateInfo.date"
			:insert="dateInfo.insert"
			:lunar="dateInfo.lunar"
			:startDate="dateInfo.startDate"
			:endDate="dateInfo.endDate"
			:range="dateInfo.range"
			@confirm="confirm"
			@close="close"
		/>
		<view class="bottom_content"><button class="primary blue" @tap="submit">提交</button></view>
	</view>
</template>

<script>
import uniCalendar from '@/components/uni-calendar/uni-calendar.vue';
import uniIcon from '@/components/uni-icons/uni-icons.vue';
import ikingSelect from '@/components/iking-select.vue';
function changeDate(e, index) {
	console.log(e, index);
}
function nowDate() {
	let nowDate = new Date();
	return nowDate.getFullYear() + '-' + ('0' + (nowDate.getMonth() + 1)).slice(-2) + '-' + ('0' + nowDate.getDate()).slice(-2);
}
function nowTime() {
	let nowDate = new Date();
	return ('0' + nowDate.getHours()).slice(-2) + ':' + ('0' + nowDate.getMinutes()).slice(-2);
}
export default {
	components: {
		uniCalendar,
		uniIcon,
		ikingSelect
	},
	data() {
		return {
			userType: this.$userInfo().userType,
			action: 1,
			date: '',
			mapshow: false,
			map: null,
			taskDesc: '',
			deviceNum: '1',
			imageList: [],
			sourceTypeIndex: 1,
			sourceType: ['拍照', '相册', '拍照或相册'],
			sizeTypeIndex: 2,
			sizeType: ['压缩', '原图', '压缩或原图'],
			countIndex: 8,
			count: [1, 2, 3, 4, 5, 6, 7, 8, 9],
			deviceList: [],
			dateInfo: {
				date: nowDate(),
				startDate: nowDate(),
				time: nowTime(),
				endDate: '',
				lunar: false,
				range: false,
				insert: false,
				selected: []
			},
			cycleTypeIndex: 0,
			cycleTypeList: ['每月', '每周', '每日'],
			cycleList: [
				{
					time: '09:09',
					date: 0,
					dateSpan: '1'
				}
			],
			cycleDateList: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
			week: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
			dateList: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
			latitude: 39.119929,
			longitude: 117.752705,
			address: '',
			covers: [
				{
					latitude: 39.9085,
					longitude: 116.39747,
					// #ifdef APP-PLUS
					iconPath: '../../../static/app-plus/location@3x.png',
					// #endif
					// #ifndef APP-PLUS
					iconPath: '../../../static/location.png'
					// #endif
				}
			],
			polyline: [
				{
					points: [
						{
							latitude: 39.9085,
							longitude: 116.39747
						},
						{
							latitude: 39.9,
							longitude: 116.39
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
			polygons: [
				{
					points: [{}]
				}
			],
			indicatorStyle: `height: ${Math.round(uni.getSystemInfoSync().screenWidth / (750 / 100))}px;`
		};
	},
	watch: {
		cycleTypeIndex(val) {
			console.log(val);
			var me = this;
			switch (Number(val)) {
				case 0:
					me.cycleDateList = me.dateList;
					break;
				case 1:
					me.cycleDateList = me.week;
					break;
				case 2:
					me.cycleDateList = [];
					break;
				default:
					break;
			}
			console.log(me.cycleList);
			me.cycleList = me.cycleList.map(value => {
				if (!me.cycleDateList[value.date]) {
					value.date = 0;
				}
				value.dateSpan = me.cycleDateList[value.date];
				return value;
			});
			console.log(me.cycleDateList);
		}
	},
	computed: {
		taskType() {
			if (this.action == 1) {
				return 'timeliness';
			}
			if (this.action == 2) {
				return 'cycle';
			}
			if (this.action == 3) {
				return 'urgent';
			}
		},
		cycleType() {
			if (this.cycleTypeIndex == 0) {
				return 'month';
			}
			if (this.cycleTypeIndex == 1) {
				return 'week';
			}
			if (this.cycleTypeIndex == 2) {
				return 'date';
			}
		}
	},
	methods: {
		previewImage: function(e) {
			var current = e.target.dataset.src;
			uni.previewImage({
				current: current,
				urls: this.imageList
			});
		},
		chooseImage: async function() {
			if (this.imageList.length === 9) {
				let isContinue = await this.isFullImg();
				console.log('是否继续?', isContinue);
				if (!isContinue) {
					return;
				}
			}
			uni.chooseImage({
				sourceType: this.sourceType[this.sourceTypeIndex],
				sizeType: this.sizeType[this.sizeTypeIndex],
				count: this.imageList.length + this.count[this.countIndex] > 9 ? 9 - this.imageList.length : this.count[this.countIndex],
				success: res => {
					this.imageList = this.imageList.concat(res.tempFilePaths);
				}
			});
		},
		isFullImg: function() {
			return new Promise(res => {
				uni.showModal({
					content: '已经有9张图片了,是否清空现有图片？',
					success: e => {
						if (e.confirm) {
							this.imageList = [];
							res(true);
						} else {
							res(false);
						}
					},
					fail: () => {
						res(false);
					}
				});
			});
		},
		uploadImage() {
			let imgs = this.imageList.map((value, index) => {
				return {
					name: 'image' + index,
					uri: value
				};
			});
			var me = this;
			this.$request
				.uploadFile('/files/ref', imgs)
				.then(data => {
					if (data.flag == '0') {
						me.referenceList = data.data;
						uni.showToast({
							title: '上传成功'
						});
					}
				});
		},
		//切换任务类型
		active(num) {
			this.action = num;
		},
		//map获取坐标
		regionchange(e) {
			// uni.getCenterLocation({
			// 	success:function(value){
			// 		console.log(value)
			// 	}
			// })
		},
		//选择巡检类型 每月，每日，没星期
		changeCycleType(e) {
			this.cycleTypeIndex = e.detail.value;
		},
		//选择巡检时间日期
		changeDate(e, index) {
			this.cycleList[index].date = e.detail.value;
			this.cycleList[index].dateSpan = this.cycleDateList[e.detail.value];
			console.log(this.cycleList[index]);
		},
		//选择巡检时间时分
		selectTime(e, index) {
			this.cycleList[index].time = e.detail.value;
			console.log(e, index);
		},
		//增加巡检时间
		addcycle(index) {
			this.cycleList.push({
				time: '09:00',
				date: 0,
				dateSpan: this.cycleDateList[0]
			});
		},
		//删除巡检时间
		delcycle(index) {
			console.log(index);
			console.log(this.cycleList);
			this.cycleList.splice(index, 1);
		},
		//点击地图
		tap(e) {
			this.polyline[0].points.push({
				latitude: e.detail.latitude,
				longitude: e.detail.longitude
			});
			var me = this;
			uni.chooseLocation({
				success: function(e) {
					console.log(e);
					me.latitude = e.latitude;
					me.longitude = e.longitude;
					me.covers[0].longitude = e.longitude;
					me.covers[0].latitude = e.latitude;
					me.address = e.address.replace('天津市滨海新区', '') + ('0' + new Date().getHours()).substr(-2) + ':' + ('0' + new Date().getMinutes()).substr(-2);
				}
			});
		},
		confirm(e) {
			console.log('confirm 返回:', e);
			this.dateInfo.date = e.fulldate;
			this.dateInfo.time = e.time;
			this.mapshow = false;
		},
		close() {
			this.mapshow = false;
		},
		open() {
			this.$refs.calendar.open();
			this.mapshow = true;
		},
		bindChange(e) {
			this.deviceNum = e.detail.value[0] + 1;
			console.log(this.deviceNum);
		},
		submit() {
			var me = this;
			uni.hideKeyboard();
			uni.showModal({
				// title:"是否提交任务？",
				content: '是否提交任务？',
				confirmText: '确定',
				cancelText: '取消',
				success: function(res) {
					if (res.confirm) {
						console.log('用户点击确定');
						me.submitData();
					} else if (res.cancel) {
						console.log('用户点击取消');
					}
				}
			});
		},
		submitData() {
			this.$request
				.post('/task/taskApply', {
					// pkId
					pkId: this.taskId,
					taskName: this.address,
					taskType: this.taskType,
					taskDesc: this.taskDesc,
					startTime: this.dateInfo.date + ' ' + this.dateInfo.time,
					cycleType: this.cycleType,
					cycleList: this.cycleList.map(val => {
						val.week = val.date;
						return val;
					}),
					deviceNum: this.deviceNum,
					mapType: 'line',
					mapList: [
						{
							lng: this.longitude,
							lat: this.latitude
						}
					],
					referenceList:this.referenceList
				})
				.then(res => {
					console.log(res);
					uni.showToast({
						title: '提交成功'
					});
					uni.navigateBack();
				});
		},
		getTaskDetail(id) {
			var me = this;
			return this.$request
				.post('/task/taskDetail', {
					pkId: id
				})
				.then(res => {
					console.log(res);
					let data = res.data;
					if (res.flag == 0) {
						this.taskInfo = data;
						if (data.mapType == 'area') {
							this.polygons.points = data.mapList;
						}
						this.address = data.name;
						this.latitude = this.taskInfo.mapList[0].lat;
						this.longitude = this.taskInfo.mapList[0].lng;
						me.covers[0].longitude = this.longitude;
						me.covers[0].latitude = this.latitude;
						switch (data.type) {
							case 'timeliness':
								this.action = 1;
								this.dateInfo.date = data.startTime.split(' ')[0];
								this.dateInfo.time = data.startTime.split(' ')[1];
								this.taskDesc = data.taskDesc;
								break;
							case 'cycle':
								this.action = 2;
								this.cycleList = data.cycleList;
								if (data.cycleType == 'month') {
									this.cycleList = this.cycleList.map(function(val) {
										val.date = Number(val.date) - 1;
										return val;
									});
									this.cycleTypeIndex = 0;
								} else if (data.cycleType == 'week') {
									this.cycleList = this.cycleList.map(function(val) {
										val.date = Number(val.week) - 1;
										return val;
									});
									this.cycleTypeIndex = 1;
								} else {
									this.cycleTypeIndex = 2;
								}
								break;
							case 'urgent':
								this.action = 3;
								break;
							default:
								break;
						}
					}
				});
		}
	},
	onLoad(option) {
		console.log(option);
		//判断是否为修改
		if (option.type == 'revision') {
			this.taskId = option.taskId;
		}
		if (option.taskId) {
			this.getTaskDetail(option.taskId);
		} else {
			var me = this;
			uni.getLocation({
				type: 'gcj02',
				geocode: true,
				success: function(e) {
					console.log(e);
					me.latitude = e.latitude;
					me.longitude = e.longitude;
					me.covers[0].longitude = e.longitude;
					me.covers[0].latitude = e.latitude;
					me.address = e.address.street + e.address.poiName + ('0' + new Date().getHours()).substr(-2) + ':' + ('0' + new Date().getMinutes()).substr(-2);
				}
			});
		}
	},
	onReady() {
		uni.loadFontFace({
			family: 'ikingFont',
			// 本地字体路径需转换为平台绝对路径
			// #ifdef APP-PLUS
			source: `url(${plus.io.convertLocalFileSystemURL('/static/font/SourceHanSansCN-Bold.otf')})`,
			// #endif
			// #ifdef H5
			source: `url(${'/static/font/SourceHanSansCN-Bold.otf'})`,
			// #endif
			success() {
				console.log('success');
			},
			fail(e) {
				console.log('fail', JSON.stringify(e));
			}
		});
		var me = this;
		// this.map = uni.createMapContext('map1', this);
		if (this.userType == 'dronePilot') {
			this.action = 3;
		}
	}
};
</script>

<style lang="scss">
.content {
	padding: 0 20upx 112upx 20upx;
	height: calc(100% - 112upx);
}
.type_content {
	display: flex;
	justify-content: space-between;
	align-items: center;
	width: 100%;
	margin-top: 51upx;
}

.cycleType {
	.mini-btn {
		width: 160upx !important;
		margin: 0 30upx 0 40upx;
	}
}
.cycleContent {
	display: flex;
	justify-content: flex-start;
	align-items: center;
	> .pick1 {
		.mini-btn {
			width: 160upx !important;
			margin: 0 20upx 0 0upx;
			padding: 10upx;
		}
	}
	.pick2 {
		.mini-btn {
			display: inline-flex !important;
			width: 100upx !important;
			padding: 0 10upx 0 16upx !important;
			&.ikingSelect1 {
				margin: 0 10upx 0 0upx !important;
			}
			&.ikingSelect2 {
				margin: 0 0upx 0 10upx !important;
			}
		}
	}
	.addCycle,
	.delCycle {
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
	.delCycle {
		background-color: #fa3043;
	}
}
.task_content {
	margin-top: 26.8upx;
	background-color: #fff;
	border-radius: 13upx;
	padding: 37upx 20upx;
	color: #2ba3ef;
	box-sizing: border-box;
}
.map {
	width: 100%;
	height: 380upx;
	margin-top: 42upx;
	margin-bottom: 33upx;
}
.textarea {
	margin-top: 33upx;
}
.bottom_content {
	position: fixed;
	bottom: 0;
	width: 100%;
	height: 112upx;
	border-top: 2upx solid #e5e5e5;
	background-color: #fff;
	margin-left: -22.5upx;
	box-sizing: content-box;
	display: flex;
	justify-content: center;
	align-items: center;
}
.calendar {
	z-index: 10;
}
picker-view {
	width: 100%;
	height: 200upx;
	margin-top: 20upx;
	color: #333;
}
picker-view-column view {
	display: flex;
	justify-content: center;
	align-items: center;
}
</style>
