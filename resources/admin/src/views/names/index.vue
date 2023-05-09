<template>
  <div class="app-container">
    <el-table :key="tableKey" v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" width="60px" label="序号">
        <template slot-scope="{ row }">
          <el-checkbox v-model="showReviewer" class="filter-item" @change="tableKey=tableKey+1">
          </el-checkbox>
        </template>
      </el-table-column>

      <el-table-column align="left" width="120px" label="域名">
        <template slot-scope="{ row }">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="left" label="单位名称">
        <template slot-scope="{ row }">
          <span>{{ row.company_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" width="80px" label="单位性质">
        <template slot-scope="{ row }">
          <span>{{ row.beian_type }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" width="110px" label="过期时间">
        <template slot-scope="{ row }">
          <span>{{ row.expired_at }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="联系方式">
        <template slot-scope="{ row }">
          <span>{{ row.phone }}</span>
        </template>
      </el-table-column>

      <el-table-column align="left" width="120px" label="网站名称">
        <template slot-scope="{ row }">
          <span>{{ row.site_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" width="100px" label="审核时间">
        <template slot-scope="{ row }">
          <span>{{ row.beian_at }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" width="100px" label="注册时间">
        <template slot-scope="{ row }">
          <span>{{ row.logon_at }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" width="100px" label="更新时间">
        <template slot-scope="{ row }">
          <span>{{ row.updated_at }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" width="240px" label="操作">
        <template slot-scope="{ row }">
          <el-button type="primary" size="mini" @click="handleUpdate(row)">
            编辑
          </el-button>
          <el-button  size="mini" @click="">
            查看
          </el-button>
          <el-button  size="mini" @click="">
            移动
          </el-button>
        </template>
      </el-table-column>

    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.page_size" @pagination="getList" />

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="70px" style="width: 400px; margin-left:50px;">
        <el-form-item label="域名" prop="name">
          <el-input v-model="temp.name" />
        </el-form-item>
        <el-form-item label="注册时间" prop="logon_at">
          <el-input v-model="temp.logon_at" />
        </el-form-item>
        <el-form-item label="过期时间" prop="expired_at">
          <el-input v-model="temp.expired_at" />
        </el-form-item>
        <el-form-item label="单位名称" prop="company_name">
          <el-input v-model="temp.company_name" />
        </el-form-item>
        <el-form-item label="单位性质" prop="beian_type">
          <el-input v-model="temp.beian_type" />
        </el-form-item>
        <el-form-item label="备案号" prop="beian_name">
          <el-input v-model="temp.beian_name" />
        </el-form-item>
        <el-form-item label="网站名称" prop="site_name">
          <el-input v-model="temp.site_name" />
        </el-form-item>
        <el-form-item label="审核时间" prop="beian_at">
          <el-input v-model="temp.beian_at" />
        </el-form-item>
        <el-form-item label="联系方式" prop="name">
          <el-input v-model="temp.phone" type="textarea" placeholder="例如：张三：18983647923" />
        </el-form-item>
        <el-form-item label="备注" prop="notes">
          <el-input v-model="temp.notes" type="textarea" placeholder="例如：已联系，加了微信，没有加微信，不卖" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          取消
        </el-button>
        <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
          确认
        </el-button>
      </div>
    </el-dialog>

  </div>
</template>

<script>
import { query_names, editName } from '@/api/names'
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
      tableKey: 0,
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        page_size: 50,
        is_beian: 1
      },
      temp:{

      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑'
      },
      rules: {
        type: [{ required: true, message: 'type is required', trigger: 'change' }],
        timestamp: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }],
        title: [{ required: true, message: 'title is required', trigger: 'blur' }]
      },
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
        //console.log(response.update_names);
      })
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row)
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    updateData(){
      this.$refs['dataForm'].validate((valid) => {
        console.log(this.temp);
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          editName(tempData).then((response) => {
            const index = this.list.findIndex(v => v.id === this.temp.id)
            this.list.splice(index, 1, this.temp)
            this.dialogFormVisible = false
            this.$notify({
              title: '成功',
              message: '数据更新成功',
              type: 'success',
              duration: 2000
            })
          })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .edit-input {
    padding-right: 100px;
  }
  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
  .el-table .cell span{

  }
</style>
