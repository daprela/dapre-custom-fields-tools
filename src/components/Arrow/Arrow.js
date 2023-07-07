import React from "react";
import styles from "./arrow.module.scss";

function Arrow({ className, title, content, arrowClick: harrowClickProp }) {
  function handleArrowClick(e) {
    let index;

    if (content === "-") {
      index = e.target.parentElement.nextElementSibling.dataset.index;
    } else {
      index = e.target.parentElement.dataset.index;
    }
    harrowClickProp(index, content);
  }

  return (
    <button
      className={`${className} ${styles.arrow}`}
      title={title}
      onClick={handleArrowClick}
    >
      {content}
    </button>
  );
}

export default Arrow;
