# encoding: utf-8
# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ name: 'Chicago' }, { name: 'Copenhagen' }])
#   Mayor.create(name: 'Emanuel', city: cities.first)

BusLine.delete_all
Stop.delete_all

BusLine.create([{
  title: '津山線',
  description: '高下・スポーツセンター線・東一宮車庫線（高下 ・ 吉ヶ原～津山～リージョンセンター ～ スポーツセンター ・ 東一宮車庫）',
  status: 0,
}])


Stop.create([{
  title: '高下発',
  x: 134.10685778,
  y: 34.92651722 ,
  bus_line_id: 1,
  times: '06:49<br>07:46<br>8:58<br>10:28<br>15:38<br>16:03<br>17:08<br>',
}])

Stop.create([{
  title: '王子',
  x: 134.11676861,
  y: 34.93326969 ,
  bus_line_id: 1,
  times: '06:54<br>07:51<br>09:03<br>10:33<br>15:43<br>16:08<br>17:13<br>',
}])

Stop.create([{
  title: '吉ヶ原',
  x: 134.07834986,
  y: 34.94166397 ,
  bus_line_id: 1,
  times: '07:05<br>08:02<br>09:14<br>10:44<br>15:54<br>16:19<br>17:24<br>',
}])

Stop.create([{
  title: '久木橋',
  x: 134.0580525,
  y: 34.95148833 ,
  bus_line_id: 1,
  times: '07:12<br>08:09<br>09:21<br>10:51<br>16:01<br>16:26<br>17:31<br>',
}])

Stop.create([{
  title: '大戸',
  x: 134.03473333,
  y: 34.98006 ,
  bus_line_id: 1,
  times: '07:19<br>08:16<br>09:28<br>10:58<br>16:08<br>16:33<br>17:38<br>',
}])

Stop.create([{
  title: '津山広域バスセンター',
  x: 134.00258594,
  y: 35.05507782 ,
  bus_line_id: 1,
  times: '07:45<br>08:05<br>08:42<br>09:50<br>10:40<br>11:20<br>14:20<br>16:30<br>16:55<br>18:00',
}])

Stop.create([{
  title: '市役所西',
  x: 134.00323,
  y: 35.06886944 ,
  bus_line_id: 1,
  times: '07:54<br>08:14<br>08:51<br>09:59<br>10:49<br>11:29<br>14:29<br>16:39<br>17:04<br>18:11',
}])

Stop.create([{
  title: '上河原',
  x: 134.00361972,
  y: 35.08454667 ,
  bus_line_id: 1,
  times: '07:59<br>08:19<br>08:56<br>10:04<br>10:54<br>11:34<br>14:34<br>16:44<br>17:09<br>18:16',
}])

Stop.create([{
  title: 'リージョンセンター前',
  x: 134.00354306,
  y: 35.09177889 ,
  bus_line_id: 1,
  times: '08:02<br>08:59<br>10:07<br>11:37<br>16:47<br>17:12<br>18:19<br>',
}])

Stop.create([{
  title: '沼住居跡入口 津山高専入口',
  x: 134.01238667,
  y: 35.08430389,
  bus_line_id: 1,
  times: '08:06<br>09:03<br>10:11<br>11:41<br>16:51<br>17:16<br>18:23<br>',
}])

Stop.create([{
  title: 'スポーツセンター前',
  x: 133.73189139,
  y: 35.2913975,
  bus_line_id: 1,
  times: '08:07<br>09:04<br>10:12<br>11:42<br>16:52<br>17:17<br>18:24<br>',
}])

Stop.create([{
  title: '東一宮車庫着',
  x: 133.99488933,
  y: 35.10240927,
  bus_line_id: 1,
  times: '08:24<br>10:59<br>14:39',
}])
