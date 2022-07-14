<template>
  <div id="layout">
    <a-spin tip="Loading..."
            :spinning="isLoading">
      <a-layout>
        <a-layout-header>Metocean Data Summary</a-layout-header>
        <a-layout-content>
          <a-select mode="tags"
                    class="ml-10"
                    :maxTagCount="maxTagCount"
                    placeholder="Please select field"
                    v-model="selectedField"
                    style="width: 400px">
            <a-select-option v-for="v in selectList"
                             :value="v.abbr"
                             :key="v.abbr">
              {{ v.name }}
            </a-select-option>
          </a-select>
          <a-button class="ml-10"
                    type="primary"
                    @click="searchSubmit">
            Filter
          </a-button>
          <a-switch class="mlr-10"
                    v-model="syncTable"
                    @change="searchSubmit" />Sync Table
          <a-row>
            <div id="data-chart"></div>
          </a-row>
          <a-row class="mt-10">
            <a-table class="data-table"
                     :columns="columns"
                     :data-source="data">
              <a slot="name"
                 slot-scope="text">{{ text }}</a>
            </a-table>
          </a-row>
        </a-layout-content>
      </a-layout>
    </a-spin>
  </div>
</template>

<script>
import { getFieldList, getFieldValue } from '@/api/data.js'
import * as echarts from 'echarts'
import $ from 'jquery'
export default {
  name: 'Metocean',
  data() {
    return {
      isLoading: false,
      maxTagCount: 2,
      chart: null,
      columns: [
        {
            title: 'Date & Time',
            dataIndex: 'date',
            key: 'date',
        }
      ],
      selectList: [],
      selectedField: [],
      legend: [],
      data: [],
      series: [],
      xAxis: [],
      chartData: [],
      syncTable: false
    }
  },
  mounted() {
    this.getColumns()
    this.getDataValue()
  },
  methods: {
    getColumns() {
      this.isLoading = true
      getFieldList().then(response => {
          // console.log(response)
          if (response.success) {
              this.selectList = response.data
              response.data.forEach( e=> {
                 this.columns.push(
                  {
                    title: e.name + ' / ' + e.unit,
                    dataIndex: e.abbr,
                    key: e.abbr
                  }
                 )
              });
          } else {
            this.$message.success(
              response.message,
              10,
            );
          }
      })
      
    },
    getDataValue() {
      
      getFieldValue().then(res => {
        if (res.success) {
          this.data = res.data.data
          this.xAxis = res.data.key
          this.legend = res.data.fields
          this.chartData = res.data.chartData
          res.data.fields.forEach( e=> {
            this.series.push({
              name: e,
              data: res.data.chartData[e],
              type: 'line'
            })
          })
          this.initChart()
          setTimeout(() => {
            this.isLoading = false
          }, 2000)
        }
      })
    },
    searchSubmit() {
      this.isLoading = true
      if (this.selectedField.length) {
        this.series = []
        this.legend = []
        this.columns = [
           {
            title: 'Date & Time',
            dataIndex: 'date',
            key: 'date',
          }
        ]
        this.selectList.forEach( e=> {
          if (this.selectedField.indexOf(e.abbr) !== -1) {
            this.series.push({
              name: e.name,
              data: this.chartData[e.name],
              type: 'line'
            })
            this.legend.push(e.name)
            if (this.syncTable) {
              this.columns.push({
                title: e.name + ' / ' + e.unit,
                dataIndex: e.abbr,
                key: e.abbr
              })
            }
          }
          if (!this.syncTable) {
            this.columns.push({
                title: e.name + ' / ' + e.unit,
                dataIndex: e.abbr,
                key: e.abbr
              })
          }
        })
        this.initChart()
      }
      this.isLoading = false
    },
    resize () {
      this.chart.resize()
    },
    initChart() {
      if (!this.chart) {
        this.chart = echarts.init(document.getElementById('data-chart'), 'primary')
      }
      // let that = this
      
      let option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
            label: {
              backgroundColor: '#6a7985'
            }
          },
          backgroundColor: '#ffffff',
          position: function (pos, params, dom, rect, size) {
            let obj = { top: pos[1] - 270 }
            if (pos[0] < size.viewSize[0] / 2) {
              obj['left'] = pos[0] - 50
            } else {
              obj['right'] = size.viewSize[0] - pos[0] - 50
            }
            return obj
          },
          padding: 10
        },
        legend: {
          type: 'scroll',
          bottom: '0px',
          icon: 'pin',
          data: this.legend
        },
        grid: {
          left: '3%',
          right: '4%',
          top: '10px',
          bottom: '100px',
          containLabel: true
        },
        xAxis: [
          {
            type: 'category',
            boundaryGap: false,
            data: this.xAxis
          }
        ],
        axisLabel: {
          color: '#999',
          fontSize: 10
        },
        dataZoom: {
          bottom: '50px',
          fillerColor: 'rgba(0, 222,0, 0.5)',
          handleStyle: {
            color: 'rgba(0, 222,0, 0.5)'
          },
          moveHandleStyle: {
            color: 'rgba(0, 222,0, 0.5)'
          }
        },
        yAxis: [
          {
            type: 'value',
            splitLine: {
              show: false
            }
          }
        ],
        series: this.series
      }
     
      this.chart.setOption(option, true)

      $(window).on('resize', this.resize)
      if (this.chartHeight) {
        this.chart.resize({ height: this.chartHeight })
      }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.ant-layout {
  height: 100vh;
  padding: 0 15px;
}
.ant-layout-header {
  background: none;
  font-size: 20px;
  color: #000;
  font-weight: 700;
}
.ant-layout-content {
  min-height: calc(100% - 64px);
}
#data-chart {
  height: 40vh;
}

.ml-10 {
  margin-left: 10px;
}
.mlr-10 {
  margin-left: 10px;
  margin-right: 10px;
}
.mt-10 {
  margin-top: 10px;
}
.ant-spin-nested-loading > div > .ant-spin {
  max-height: 100%;
}
</style>
