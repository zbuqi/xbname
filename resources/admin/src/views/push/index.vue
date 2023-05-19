<template>
    <div class="app-container">
        <el-form class="push-names-wrap" ref="form">
          <el-form-item>
            <el-input class="push-names" type="textarea" v-model="desc"></el-input>
            <div class="push-names-num">
              <el-button type="primary" @click="addTmpNames">立即上传</el-button>
              <div class="ym-num"><p>待提交：<b>{{ ymnum }}</b> 条域名</p></div>
            </div>
          </el-form-item>
        </el-form>
        <div class="ym-num">
          <p>本次共提交备案域名：<b>{{ baym }}</b>个，还剩下：<b>{{ no_query }}</b>个没有查询。</p>
          <el-button type="primary" size="mini" @click="addBeianName">立即上传</el-button>
        </div>

      <el-table v-loading="listLoading" :data="tableData" border fit highlight-current-row style="width: 100%">
        <el-table-column align="left" label="域名">
          <template slot-scope="{ row }">
            <span>{{ row.name }}</span>
          </template>
        </el-table-column>

        <el-table-column align="left" label="单位名称">
          <template slot-scope="{ row }">
            <span>{{ row.company_name }}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" label="单位性质">
          <template slot-scope="{ row }">
            <span>{{ row.beian_type }}</span>
          </template>
        </el-table-column>

        <el-table-column align="left" label="网站名称">
          <template slot-scope="{ row }">
            <span>{{ row.site_name }}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" label="审核时间">
          <template slot-scope="{ row }">
            <span>{{ row.beian_at }}</span>
          </template>
        </el-table-column>

      </el-table>
    </div>
</template>

<script>
import { addTmpNames, tmpNames, addBeianName } from '@/api/names'

export default{
  data() {
    return {
      desc: '',
      ymnum: 0,
      no_query: 0,
      names: '',
      baym: 0,
      listLoading: true,
      tableData: []
    }
  },
  created() {
    this.getList()
  },
  updated() {
    var names = this.desc.split(/\n/).filter(function(s){
      return s && s.trim()
    })
    this.ymnum = names.length
    this.names = names
  },
  methods: {
    addTmpNames() {
      if (this.names != "") {
        addTmpNames(this.names).then((res) => {
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
    },
    addBeianName() {
      addBeianName("156489").then((res) => {
        if (res.data == 1) {
          this.$message({
            type: "success",
            message: "提交成功!"
          })
          console.log(res.data);
        }
      })
    },
    getList() {
      tmpNames().then((res) => {
        if (res.code == 20000) {
          this.tableHeader = res.content[0]
          this.tableData = res.content
          this.no_query = res.no_query
          this.baym = res.content.length
        }
        this.listLoading = false
        console.log(this.tableData)
      })
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
.ym-num b{
  color: red;
  font-size:24px;
  padding:0 5px;
}
.ym-num p{
  display:inline-block;
}
.ym-num .el-button{

}
</style>
