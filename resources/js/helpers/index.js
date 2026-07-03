export function getObjectValue(key, object){
    if(typeof key == 'string')
        return object[key]

    if (Array.isArray(key))
        return key.length == 1
            ? object[key[0]]
            : getObjectValue(key.slice(1, key.length), object[key[0]])

}

export function fixOverflow(el) {
    if (typeof el !== 'object') {
        console.error('Аргумент не является объектом!')
        return
    }

    el.style.maxHeight      = 'none'
    el.style.minHeight      = 'none'
    el.style.display        = 'flex'

    let isOverflow = false
    const rect = el.getBoundingClientRect();

    const overflowBottom = rect.bottom > window.innerHeight;
    const overflowRight  = rect.right > window.innerWidth;

    // Если вылез снизу за экран
    if(overflowBottom) {
        el.style.top    = 'auto'
        el.style.bottom = '100%';

        isOverflow = true
    }
    // Другие
    // .....

    el.style.removeProperty('max-height');
    el.style.removeProperty('min-height');

    void el.offsetHeight

    return isOverflow
}

window.fixOverflow = fixOverflow;

