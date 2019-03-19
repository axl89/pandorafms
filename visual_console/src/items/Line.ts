import { UnknownObject, Position, Size } from "../types";
import { parseIntOr, notEmptyStringOr } from "../lib";
import Item, { ItemType, ItemProps, itemBasePropsDecoder } from "../Item";

interface LineProps extends ItemProps {
  // Overrided properties.
  readonly type: ItemType.LINE_ITEM;
  label: null;
  isLinkEnabled: false;
  parentId: null;
  aclGroupId: null;
  // Custom properties.
  startPosition: Position;
  endPosition: Position;
  lineWidth: number;
  color: string | null;
}

/**
 * Build a valid typed object from a raw object.
 * This will allow us to ensure the type safety.
 *
 * @param data Raw object.
 * @return An object representing the item props.
 * @throws Will throw a TypeError if some property
 * is missing from the raw object or have an invalid type.
 */
export function linePropsDecoder(data: UnknownObject): LineProps | never {
  return {
    ...itemBasePropsDecoder(data), // Object spread. It will merge the properties of the two objects.
    type: ItemType.LINE_ITEM,
    label: null,
    isLinkEnabled: false,
    parentId: null,
    aclGroupId: null,
    // Initialize Position & Size.
    x: 0,
    y: 0,
    width: 0,
    height: 0,
    // Custom properties.
    startPosition: {
      x: parseIntOr(data.startX, 0),
      y: parseIntOr(data.startY, 0)
    },
    endPosition: {
      x: parseIntOr(data.endX, 0),
      y: parseIntOr(data.endY, 0)
    },
    lineWidth: parseIntOr(data.lineWidth, 0),
    color: notEmptyStringOr(data.color, null)
  };
}

export default class Line extends Item<LineProps> {
  /**
   * @override
   */
  public constructor(props: LineProps) {
    /*
     * We need to override the constructor cause we need to obtain
     * the
     * box size and position from the start and finish points
     * of the line.
     */
    super({
      ...props,
      ...Line.extractBoxSizeAndPosition(props)
    });
  }

  /**
   * @override
   * To create the item's DOM representation.
   * @return Item.
   */
  public createDomElement(): HTMLElement {
    const element: HTMLDivElement = document.createElement("div");
    element.className = "line";

    const svgNS = "http://www.w3.org/2000/svg";
    // SVG container.
    const svg = document.createElementNS(svgNS, "svg");
    // Set SVG size.
    // svg.setAttribute("width", this.props.width.toString());
    // svg.setAttribute("height", this.props.height.toString());
    const line = document.createElementNS(svgNS, "line");
    line.setAttribute("x1", `${this.props.startPosition.x - this.props.x}`);
    line.setAttribute("y1", `${this.props.startPosition.y - this.props.y}`);
    line.setAttribute("x2", `${this.props.endPosition.x - this.props.x}`);
    line.setAttribute("y2", `${this.props.endPosition.y - this.props.y}`);
    line.setAttribute("stroke", this.props.color || "black");
    line.setAttribute("stroke-width", this.props.lineWidth.toString());
    line.setAttribute("stroke-linecap", "round");

    svg.append(line);
    element.append(svg);

    return element;
  }

  /**
   * Extract the size and position of the box from
   * the start and the finish of the line.
   * @param props Item properties.
   */
  private static extractBoxSizeAndPosition(props: LineProps): Size & Position {
    return {
      width: Math.abs(props.startPosition.x - props.endPosition.x),
      height: Math.abs(props.startPosition.y - props.endPosition.y),
      x: Math.min(props.startPosition.x, props.endPosition.x),
      y: Math.min(props.startPosition.y, props.endPosition.y)
    };
  }
}
