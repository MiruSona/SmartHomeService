using UnityEngine;
using Vuforia;
using System.Collections;

public class TargetManager : MonoBehaviour, ITrackableEventHandler
{
    //타겟 인식 여부 받아오는 클래스
    private TrackableBehaviour mTrackableBehaviour;

    //인식 여부
    private bool detected = false;

    //초기화
    void Start()
    {
        mTrackableBehaviour = GetComponent<TrackableBehaviour>();
        if (mTrackableBehaviour)
        {
            mTrackableBehaviour.RegisterTrackableEventHandler(this);
        }
    }

    //인식 여부 처리
    public void OnTrackableStateChanged(TrackableBehaviour.Status previousStatus, TrackableBehaviour.Status newStatus)
    {
        if (newStatus == TrackableBehaviour.Status.DETECTED || newStatus == TrackableBehaviour.Status.TRACKED)
        {
            detected = true;
        }
        else
        {
            detected = false;
        }
    }

    //인식 여부 반환
    public bool GetDetected()
    {
        return detected;
    }
}
