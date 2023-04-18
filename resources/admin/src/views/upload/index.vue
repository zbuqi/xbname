<template>
  <div class="app-container">
    <el-upload
      class="upload-demo drop"
      drag
      action="https://jsonplaceholder.typicode.com/posts/"
      :before-upload="beforeYmUpload"
    >
      <i class="el-icon-upload"></i>
      <div class="el-upload__text">将文件拖到此处，或<em class="">点击上传</em></div>
      <div class="el-upload__tip" slot="tip">只能上传txt文件。</div>
    </el-upload>
  </div>
</template>
<script>
  export default {
    data() {
      return {

      };
    },
    methods: {
      beforeYmUpload(file){
        const isTxt = file.type === 'text/plain';
        if(!isTxt){
          this.$message.error('上传文件只能是txt格式');
        }
        return isTxt;
      }
    }
  }
</script>
<style scoped>
  .drop{
    margin: 50px auto;
    text-align: center;
  }
</style>
