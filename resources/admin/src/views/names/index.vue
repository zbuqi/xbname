<template>
  <div class="app-container">


    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" width="80px" label="序号">
        <template slot-scope="{ row }">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" width="150px" label="域名">
        <template slot-scope="{ row }">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="单位名称">
        <template slot-scope="{ row }">
          <span>{{ row.company_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="单位性质">
        <template slot-scope="row">
          <span>{{ row.beian_type }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="ICP备案号">
        <template slot-scope="{ row }">
          <span>{{ row.beian_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="网站名称">
        <template slot-scope="{ row }">
          <span>{{ row.site_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="审核时间">
        <template slot-scope="{ row }">
          <span>{{ row.beian_at }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="更新时间">
        <template slot-scope="{ row }">
          <span>{{ row.updated_at }}</span>
        </template>
      </el-table-column>

    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.page_size" @pagination="getList" />

  </div>
</template>

<script>
  import { query_names } from '@/api/names'
  import Pagination from '@/components/Pagination'

  export default {
    name: 'NamesList',
    components: { Pagination },
    filters: {
      statusFilter(status) {
        const statusMap = {
          published: 'success',
          draft: 'info',
          deleted: 'danger'
        }
        return statusMap[status]
      }
    },
    data() {
      return {
        list: null,
        total: 0,
        listLoading: true,
        listQuery: {
          page: 1,
          page_size: 50,
          is_beian: 1
        }
      }
    },
    created() {
      this.getList()
    },
    methods: {
      getList() {
        this.listLoading = true
        query_names(this.listQuery).then(response => {
          this.list = response.content.data
          this.total = response.content.total
          this.listLoading = false
          console.log(response)
        })
      }
    }
  }
</script>

<style scoped>
  .edit-input {
    padding-right: 100px;
  }
  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
</style>
