class SlugGenerator {
    protected title: string = '';
    protected slug: string = '';

    constructor(title: string) {
        this.title = title;
    }

    private sanitizeTitle(): string {
        var slug: string = '';
        
        // Change title to lower case.
        const lowerCaseTitle = this.title.toLocaleLowerCase();
        // Letter a
        slug = lowerCaseTitle.replace(/a|à|á|ả|ã|ạ|ă|ằ|ắ|ẳ|ẵ|ặ|â|ầ|ấ|ẩ|ẫ|ậ/gi, 'a');
        // Letter d
        slug = slug.replace(/đ/gi, 'd');
        // Letter e
        slug = slug.replace(/e|è|é|ẻ|ẽ|ẹ|ê|ề|ế|ể|ễ|ệ/gi, 'e');
        // Letter i
        slug = slug.replace(/i|ì|í|ỉ|ĩ|ị/gi, 'i');
        // Letter o
        slug = slug.replace(/o|ò|ó|ỏ|õ|ọ|ô|ồ|ố|ổ|ỗ|ộ|ơ|ờ|ớ|ở|ỡ|ợ/gi, 'o');
        // Letter u
        slug = slug.replace(/u|ù|ú|ủ|ũ|ụ|ư|ừ|ứ|ử|ữ|ự/gi, 'u');
        // Letter y
        slug = slug.replace(/y|ỳ|ý|ỷ|ỹ|ỵ/gi, 'y');

        slug = slug.replace(/\s*$/g, '');
        slug = slug.replace(/\\|\?|\&|\<|\>|\//g, '');
        slug = slug.replace(/\s+/g, '-');

        return slug;
    }

    public getSlug(): string {
        this.slug = this.sanitizeTitle();

        return this.slug;
    }
}

export { SlugGenerator };