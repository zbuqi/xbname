<template>
  <div class="app-container">
    <upload-excel-component :on-success="handleSuccess" :before-upload="beforeUpload" />
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
        this.tableData = results
        this.tableHeader = header
        var results = JSON.parse(JSON.stringify(results).replace(/域名/g, 'name'));
        var results = JSON.parse(JSON.stringify(results).replace(/单位名称/g, 'company_name'));
        var results = JSON.parse(JSON.stringify(results).replace(/单位性质/g, 'beian_type'));
        var results = JSON.parse(JSON.stringify(results).replace(/ICP备案号/g, 'beian_name'));
        var results = JSON.parse(JSON.stringify(results).replace(/网站名称/g, 'site_name'));
        var results = JSON.parse(JSON.stringify(results).replace(/审核时间/g, 'beian_at'));

        addNamesExcle(results).then((res)=>{
          if(res.data){
            this.$message({
              message: '域名提交成功',
              type: 'success'
            })
          }
        })


      }
    }
  }
</script>
