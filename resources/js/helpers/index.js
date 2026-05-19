export function getObjectValue(key, object){
    if(typeof key == 'string')
        return object[key]

    if (Array.isArray(key))
        return key.length == 1
            ? object[key[0]]
            : getObjectValue(key.slice(1, key.length), object[key[0]])

}
