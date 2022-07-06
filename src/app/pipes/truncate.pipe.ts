import {Pipe, PipeTransform } from '@angular/core';

@Pipe({
    name: 'truncate'
})

export class TruncatePipe implements PipeTransform {
    transform(value: any): any {
            if( value.length > 48){
                value = value.substring(0, 40) + '...';
            }

        return value;
    }
}