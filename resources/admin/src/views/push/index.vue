<template>
    <div class="app-container">
        <el-form class="push-names-wrap" ref="form">
          <el-form-item>
            <el-input class="push-names" type="textarea" v-model="desc"></el-input>
            <div class="push-names-num">
              <el-button type="primary" @click="addNames">立即上传</el-button>
              <div class="ym-num"><p>待提交：<b>{{ ymnum }}</b> 条域名</p></div>
            </div>
          </el-form-item>
        </el-form>
    </div>
</template>

<script>
import { addNames } from '@/api/names'

export default{
  data() {
    return {
      desc: '',
      ymnum: '',
      names: ''
    }
  },
  updated() {
    var names = this.desc.split(/\n/).filter(function(s){
      return s && s.trim()
    })
    this.ymnum = names.length
    return this.names = names
  },
  methods: {
    addNames() {
      if(this.names != "") {
        addNames(this.names).then((res) => {
          console.log(res)
          if (res.data) {
            this.$message({
              type: "success",
              message: "提交成功!"
            })
            this.desc = ''
          }
        })
      }else{
        this.$message({
          type: "success",
          message: "提数据为空!"
        })
      }
    }
  }
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
