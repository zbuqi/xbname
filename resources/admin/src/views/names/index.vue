<template>
  <div class="app-container">
    <el-table
      :data="tableData"
      stripe
      style="width:100%">
      <el-table-column
        prop="index"
        label="序号"
        width="">
      </el-table-column>
      <el-table-column
        prop="name"
        label="域名"
        width="">
      </el-table-column>
      <el-table-column
        prop="company_name"
        label="单位名称"
        width="">
      </el-table-column>
      <el-table-column
        prop="beian_type"
        label="单位性质"
        width="">
      </el-table-column>
      <el-table-column
        prop="beian_name"
        label="ICP备案号"
        width="">
      </el-table-column>
      <el-table-column
        prop="site_name"
        label="网站名称"
        width="">
      </el-table-column>
      <el-table-column
        prop="beian_at"
        label="审核时间"
        width="">
      </el-table-column>
      <el-table-column
        prop="updated_at"
        label="更新时间"
        width="">
      </el-table-column>

    </el-table>
  </div>
</template>
<script>
import { query_names } from '@/api/names'

export default{
  data() {
    return {
      tableData: [{
        index: '1',
        name: 'phc6.com',
        company_name: '',
        beian_type: '',
        beian_name: '',
        site_name: '',
        beian_at: '',
        updated_at: '',
      },{
        index: '2',
        name: 'enjoy.com',
        company_name: '',
        beian_type: '',
        beian_name: '',
        site_name: '',
        beian_at: '',
        updated_at: '',
      }]
    }
  },
  created() {
    this.query_names();
  },
  methods: {
    query_names() {
      query_names('123').then(response => {
        console.log(response);
      })
    }


  }
}
</script>
<style lang="scss">

</style>
