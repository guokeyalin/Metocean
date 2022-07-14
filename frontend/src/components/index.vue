<template>
  <div id="layout">
    <a-spin tip="Loading..."
            :spinning="isLoading">
      <a-layout>
        <a-layout-header>Metocean Data Summary</a-layout-header>
        <a-layout-content>
          <a-range-picker></a-range-picker>
          <a-select mode="tags"
                    class="ml-10"
                    :maxTagCount="maxTagCount"
                    placeholder="Please select"
                    :default-value="['a1', 'b2']"
                    style="width: 200px">
            <a-select-option v-for="i in 25"
                             :key="(i + 9).toString(36) + i">
              {{ (i + 9).toString(36) + i }}
            </a-select-option>
          </a-select>
          <a-button class="ml-10"
                    type="primary"
                    @click="searchSubmit">
            Search
          </a-button>
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

import * as echarts from 'echarts'
import $ from 'jquery'
export default {
  name: 'Metocean',
  data() {
    return {
      isLoading: false,
      maxTagCount: 2,
      columns: [
        {
            title: 'Date & Time',
            dataIndex: 'date',
            key: 'date',
        }
      ],
      data: [],
      series: [
        {
           name: 'Test 01',
           type: 'line',
           data: [1,2,3,4,5]
        },
        {
           name: 'Test 01',
           type: 'line',
           data: [5,6,7,8,9]
        }
      ],
      xAxis: [
        '2021-14',
        '2021-14', 
        '2021-14', 
        '2021-14', 
        '2021-14',
      ]
    }
  },
  mounted() {
    this.getColumns()
    this.getDataValue()
  },
  methods: {
    getColumns() {
      const list = [
        {
            title: 'KEY',
            dataIndex: 'key',
            key: 'key',
        }
      ]
      list.forEach(e => {
        this.columns.push(e)
      })
    },
    getDataValue() {
      this.initChart()
    },
    searchSubmit() {
      this.isLoading = true
      setTimeout(() => {
        this.isLoading = false
      }, 2000)
    },
    resize () {
      this.chart.resize()
    },
    initChart() {
      this.chart = echarts.init(document.getElementById('data-chart'), 'primary')
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
          title: {
            text: 'Income of Germany and France since 1950'
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
          padding: 0
        },
        legend: {
          bottom: '0px',
          icon: 'pin',
          data: []
        },
        grid: {
          left: '3%',
          right: '4%',
          top: '10px',
          bottom: '90px',
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
      this.chart.setOption(option)

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
.mt-10 {
  margin-top: 10px;
}
.ant-spin-nested-loading > div > .ant-spin {
  max-height: 100%;
}
</style>
