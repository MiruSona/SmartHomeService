using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class DropdownScroll : MonoBehaviour {

    //참조
    public DropdownBtn dropdown_btn;
    private Scrollbar scroll_bar;
    private Button[] buttons;
    private Text[] texts;

    //값
    private int item_num = 0;

    //초기화
    void Awake()
    {
        scroll_bar = transform.FindChild("Scrollbar").GetComponent<Scrollbar>();
        buttons = GetComponentsInChildren<Button>();
        texts = GetComponentsInChildren<Text>();

        item_num = buttons.Length;
    }
    
    //아이템 누를 경우
    public void ItemBtn(int _index)
    {
        dropdown_btn.ChangeBtn(texts[_index].text, _index);
        gameObject.SetActive(false);
    }

    //아이템 갯수 변경
    public void ChangeItemNum(int _num)
    {
        //숫자가 아이템 숫자 안넘기는지 체크
        if (_num <= item_num)
        {
            for (int i = 0; i < item_num; i++)
            {
                //원하는 갯수만큼 활성화 및 텍스트 넣기
                if (i < _num)
                    buttons[i].gameObject.SetActive(true);
                else
                    buttons[i].gameObject.SetActive(false);
            }
        }
    }

    //아이템 내용 변경
    public void ChangeItems(string[] _texts)
    {
        for (int i = 0; i < item_num; i++)
        {
            //원하는 갯수만큼 활성화 및 텍스트 넣기
            if (i < _texts.Length)
            {
                texts[i].text = _texts[i];
                buttons[i].gameObject.SetActive(true);
            }
            //아님 끄기
            else
                buttons[i].gameObject.SetActive(false);
        }
    }

    //스크롤바 초기화
    public void InitScrollBar()
    {
        scroll_bar.value = 1;
    }
}
