import { Component, OnInit,Input, EventEmitter,Output} from '@angular/core';

@Component({
  selector: 'app-modal-ui',
  templateUrl: './modal-ui.component.html',
  styleUrls: ['./modal-ui.component.css']
})
export class ModalUiComponent implements OnInit {

  @Input() isError: boolean = true;
  @Input() message:string = "Hello world"
  @Output() onClose = new EventEmitter<any>();

  constructor() { }

  ngOnInit(): void {
  }

  close(event: Event):void{
    this.onClose.emit(true)
  }

}
