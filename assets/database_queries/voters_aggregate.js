db.getCollection('voters').aggregate([
{
    '$match' : {
            '$and' : [
                {
                    'voterBaseHouseholdId' : {
                        '$in' : [
                            "Y000000011203770BRIGHTON",
                            "Y000000011203770BRIGHTON",
                            "Y000000011203770BRIGHTON",
                            "Y000000011203770BRIGHTON"
                            ]
                        }
                     }
                ]
            }
        },
        {
            '$group' : {
                    '_id' : null,
                    'voters' : {
                        '$push' : {
                                "_id" : "$_id",
                                "voterBaseHouseholdId" : "$voterBaseHouseholdId",
                                "smartId" : "$smartId",
                                "voterBaseId" : "$voterBaseId",
                                "voterId" : "$voterId",
                                "tsmartNameSuffix" : "$tsmartNameSuffix",
                                "firstName" : "$firstName",
                                "middleName" : "$middleName",
                                "lastName" : "$lastName",
                                "tsmartFullAddress" : "$tsmartFullAddress",
                                "tsmartCity" : "$tsmartCity",
                                "tsmartState" : "$tsmartState",
                                "tsmartZip" : "$tsmartZip",
                                "tsmartZip4" : "$tsmartZip4",
                                "tsmartStreetNumber" : "$tsmartStreetNumber",
                                "tsmartStreetName" : "$tsmartStreetName",
                                "tsmartStreetSuffix" : "$tsmartStreetSuffix",
                                "voterbasePhone" : "$voterbasePhone",
                                "voterbasePhoneWireless" : "$voterbasePhoneWireless",
                                "voterbaseRegistrationStatus" : "$voterbaseRegistrationStatus",
                                "voterBaseMoverStatus" : "$voterBaseMoverStatus",
                                "voterBaseMoverRegistrationStatus" : "$voterBaseMoverRegistrationStatus",
                                "dob" : "$dob",
                                "voterbaseGender" : "$voterbaseGender",
                                "voterbaseMaritalstatus" : "$voterbaseMaritalstatus",
                                "vfRegCassAddressFull" : "$vfRegCassAddressFull",
                                "vfRegCassCity" : "$vfRegCassCity",
                                "vfRegCassState" : "$vfRegCassState",
                                "vfRegCassZip" : "$vfRegCassZip",
                                "vfRegCassZip4" : "$vfRegCassZip4",
                                "vfRegCassStreetNum" : "$vfRegCassStreetNum",
                                "vfRegCassstreetName" : "$vfRegCassstreetName",
                                "vfRegCassStreetSuffix" : "$vfRegCassStreetSuffix",
                                "voterBaseEmail" : "$voterBaseEmail",
                                "absenteeballotrequest" : "$absenteeballotrequest",
                                "addedBy" : "addedBy",
                                "addedDate" : "$addedDate",
                                "addedFrom" : "$addedFrom",
                                "becomeVolunteer" : "$becomeVolunteer",
                                "daletedDate" : "$daletedDate",
                                "interestNotified" : "$interestNotified",
                                "isActive" : "$isActive",
                                "isDeleted" : "$isDeleted",
                                "isModified" : "$isModified",
                                "isVerified" : "$isVerified",
                                "modifiedDate" : "$modifiedDate",
                                "remarks" : "$remarks",
                                "sendEmail" : "$sendEmail",
                                "sendText" : "$sendText"
                            }
                        }
                }
            },
            {'$project' : {
                'voters' : {
                        '$slice' : ['$voters',0,1]
                    },
                    'totalCount' : {'$size' : '$voters'}
                }}
    ]
)