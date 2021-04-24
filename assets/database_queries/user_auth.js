db.getCollection('users').find({
    '$and' : [
        {
            'userType' : {'$in' : ['Super Admin','Admin']}
            },
            {
            'email' : {'$eq' : "admin@usvoter.com"}
            },
            {
            'password' : {'$eq' : "e10adc3949ba59abbe56e057f20f883e"}
            }
        ]
    })