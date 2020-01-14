<script>
	export default {
		onLaunch: function() {
			const value = JSON.parse(uni.getStorageSync("login_info"));
			console.log(value)
			if (value.login_state == "true") {
				this.$store.state.hasLogin = true;
				this.$store.state.userName = value.username;
			}

			console.log('App Launch');
		},
		onShow: function() {
			console.log('App Show');
		},
		onHide: function() {
			console.log('App Hide');
		},
	}
</script>

<style lang="scss">
	/*每个页面公共css */
	@import 'uni.scss';

	@font-face {
		font-family: ikingFont;
		font-weight: normal;
		font-style: normal;
		src: url('./static/font/SourceHanSansCN-Bold.otf') format('truetype');
	}

	page {
		font-family: ikingFont;
		min-height: 100%;
		display: flex;
		background-image: linear-gradient($iking-color-blue 5%, $iking-color-common 40%);
		/* #ifndef APP-NVUE */
		background-attachment: fixed;
		/* #endif */
		color: $iking-color-common;
		box-sizing: border-box;
	}

	.ikingtec_h1 {
		font-size: 53upx;
		font-weight: 700;
		margin-top: 47upx;
	}

	.title {
		font-size: 40upx;
		font-weight: 700;

		&.blue {
			color: $iking-color-blue;
		}
	}

	.content {
		display: flex;
		flex: 1;
		flex-direction: column;
		padding: 47upx 45upx;
	}

	button {
		&.primary {
			background-color: #fff;
			color: $iking-color-blue;
			font-size: 40upx;
			line-height: 40upx;
			padding: 23upx 48upx;
			margin: 0;
			font-weight: 700;
			border-radius: 15upx;
		}

		&.blue {
			background-color: $iking-color-blue;
			color: $iking-color-common;
		}
	}

	.textarea {
		background-color: #fff;
		border: 2upx solid $iking-color-blue;
		border-radius: 10upx;
		width: 100%;
		min-height: 400upx;
		color: #989898;
		padding: 20upx 40upx;
		box-sizing: border-box;
		font-size: 24upx;
	}
</style>
