<template>
    <div class="app-container">
        <el-form class="push-names-wrap" ref="form" :model="form">
          <el-form-item>
            <el-input class="push-names" type="textarea" v-model="desc"></el-input>
            <div class="push-names-num">
              <el-button type="primary" @click="onSubmit">立即上传</el-button>
              <div class="ym-num"><p>待提交：<b>{{ ymnum }}</b> 条域名</p></div>
            </div>
          </el-form-item>
        </el-form>
    </div>
</template>

<script>
export default{
    data() {
      return {
        desc: '',
        ymnum:''
      }
    },
    updated() {
      return this.ymnum = this.desc;
    },
    methods: {
      onSubmit() {
        this.$message('submit!')
      }
    },
}
</script>

<style lang="scss">
.push-names-wrap{
  margin:50px;
}
.push-names{
  width:50%;
  padding:0 10px;
  float:left;
}
.push-names .el-textarea__inner{
  min-height:180px !important;
  padding:15px;
}
.push-names-num{
  width:50%;
  float:left;
  padding:0 10px;
}
.push-names-num .ym-num{
  font-size:14px;
  color:#333;
}
.push-names-num .ym-num b{
  font-size: 18px;
  color: #ff7800;
}
</style>
