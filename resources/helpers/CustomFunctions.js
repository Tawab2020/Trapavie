export default {

    trunucate(str, len) {
        var trimmedString = str.substr(0, len);
        //re-trim if we are in the middle of a word
        trimmedString = trimmedString.substr(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" ")))
        return trimmedString;
    }

}
