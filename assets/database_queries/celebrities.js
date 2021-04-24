db.getCollection('celebrities').aggregate(
[
    {
        '$match' : {
                '$and' : [
                    {'isDeleted' : false},
                    {
                        '$or' : [
                {'name' : {'$regex' : '.*.*','$options' : 'i'}},
                {'description' : {'$regex' : '.*.*','$options' : 'i'}}
                ]
                        }
                ]
            }
        },
        {
           '$sort' : {
               'name'  : 1
               }},
   {
       '$group' : {
       '_id' : null,
       'celebrities' : {
            '$push' : {
                    '_id': '$_id',
                    'name': '$name',
                    'photo': '$photo',
                    'description': '$description',
                    'note': '$note',
                    'addedDate': '$addedDate',
                    'modifiedDate': '$modifiedDate',
                    'daletedDate': '$daletedDate',
                    'isActive': '$isActive',
                    'isModified': '$isModified',
                    'isDeleted': '$isDeleted',
                    'remarks': '$remarks',
                    'addedFrom': '$addedFrom',
                    'addedBy': '$addedBy',
                }
           }
       }
       },
      {
          '$project' : {
              'celebrities' : {'$slice' : ['$celebrities',0,10]},
              'totalCounts' : {'$size' : '$celebrities'}
              }
          } 
]
)