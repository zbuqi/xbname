<template>
  <div class="app-container">
    <upload-excel-component :on-success="handleSuccess" :before-upload="beforeUpload" />
    <div class="ym-num"><p>本次共提交：<b>{{ ymnum }}</b>个。 <span v-html="cfname"></span></p></div>
    <el-table :data="tableData" border highlight-current-row style="width: 100%;margin-top:20px;">
      <el-table-column v-for="item of tableHeader" :key="item" :prop="item" :label="item" />
    </el-table>
  </div>
</template>

<script>
import UploadExcelComponent from '@/components/UploadExcel/index.vue'
import { addNamesExcle } from '@/api/names'

export default {
  name: 'UploadExcel',
  components: { UploadExcelComponent },
  data() {
    return {
      ymnum: 0,
      cfname: '',
      tableData: [],
      tableHeader: []
    }
  },
  methods: {
    beforeUpload(file) {
      const isLt1M = file.size / 1024 / 1024 < 1
      if (isLt1M) {
        return true
      }
      this.$message({
        message: 'Please do not upload files larger than 1m in size.',
        type: 'warning'
      })
      return false
    },
    handleSuccess({ results, header }) {
      this.ymnum = results.length
      this.tableData = []
      this.tableHeader = header
      console.log(results)
      addNamesExcle(results).then((res) => {
        this.$message({
          message: res.message,
          type: 'success'
        })
        if (res.data !== 1) {
          this.tableData = res.data
          this.cfname = '提交失败，有重复域名<b>' + res.data.length + '</b>个'
        }
      })
    }
  }
}
</script>
<style lang="scss">
  .ym-num b{
    color: red;
    font-size:24px;
    padding:0 5px;
  }
</style>
