db.getCollection('celebrities').update(
 {'_id': ObjectId("5ef326c107d4d61bac34a25c")},
{ $pull: { "photoGallery" : { '_id': ObjectId("5ef43980c58d9a35005e7064") } } },
false,
true
)