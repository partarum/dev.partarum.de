class FlagWorker {

    flags;

    flagTimer;

    flagResolve;

    statusFlag = 0;

    constructor() {
        this.set(0);
        this.flagTimer = new Promise((resolve, reject) => {
            this.flagResolve = resolve;
        });
    }

    set(flag) {
        this.flags = flag;
    }

    add(flag) {
        this.flags |= flag;

        if(this.statusFlag === flag){
            this.flagResolve(flag);
        }
    }

    has(flag) {
        return !!(this.flags & flag);
    }

    get(flag) {
        return this.flags;
    }

    remove(flag) {
        this.flags &= ~flag;
    }

    toggle(flag) {
        this.has(flag) ? this.remove(flag) : this.add(flag);
    }

    status(flag) {

        this.statusFlag = flag;

        return new Promise((resolve) => {
            this.flagTimer.then((value) => {

                resolve(value);
            }).then((value) => {

                this.flagTimer = new Promise((resolve) => {
                    this.flagResolve = resolve;
                });
            });
        });
    }
}

export {FlagWorker};