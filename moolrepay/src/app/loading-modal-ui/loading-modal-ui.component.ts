import { Component,  OnInit,Input, EventEmitter,Output } from '@angular/core';

@Component({
  selector: 'app-loading-modal-ui',
  templateUrl: './loading-modal-ui.component.html',
  styleUrls: ['./loading-modal-ui.component.css']
})
export class LoadingModalUiComponent implements OnInit {

  @Input() isError: boolean = true;
  @Input() message:string = ""
  @Input() asset_path: string = '../../'

  constructor() { }

  ngOnInit(): void {
  }

 
}
